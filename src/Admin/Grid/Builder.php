<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Widgets\Pagination;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Html\Elements\Inputs\CheckBox;
use Illuminate\Pagination\LengthAwarePaginator;
use Arbory\Base\Admin\Filter\Renderer as FilterRenderer;

/**
 * Class Builder.
 */
class Builder implements Renderable
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Collection|LengthAwarePaginator
     */
    protected $items;

    /**
     * Builder constructor.
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->grid;
    }

    /**
     * @return Element|null
     */
    protected function bulkEdit(): ?Element
    {
        if (! $this->grid->hasTool('bulk-edit')) {
            return null;
        }

        $this->addBulkColumn();

        $href = $this->url('dialog', [
            'dialog' => 'confirm_bulk_edit',
        ]);
        $button = new Link($href);
        $button->withIcon('edit');
        $button->asButton('bulk-action js-bulk-edit-button ajaxbox')->title(trans('arbory::resources.bulk_edit'));

        return Html::div($button->render())->addClass('bulk-actions');
    }

    /**
     * @return Column
     */
    protected function addBulkColumn(): Column
    {
        return $this->grid->prependColumn('id', trans('arbory::resources.nr'), 1)
            ->checkable(true)
            ->display(function ($value, Column $column) {
                $cellContent = Html::span();
                $checkbox = new CheckBox($value);
                $checkbox->setValue($value);
                $checkbox->addClass('js-bulk-edit-row-checkbox');
                $checkbox->setName('bulk_edit_item_ids[]');

                return $cellContent->append($checkbox);
            });
    }

    /**
     * @return Content|string|null
     */
    protected function filter()
    {
        if (! $this->grid->hasTool('filter')) {
            return;
        }

        $filterManager = $this->grid->getFilterManager();

        return (new FilterRenderer())
            ->setManager($filterManager)
            ->render();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getTableColumns()
    {
        $tableColumns = $this->grid()->getColumns()->map(function (Column $column) {
            return $this->getColumnHeader($column);
        });

        if ($this->grid->isToolboxEnable()) {
            $tableColumns->push(Html::th(Html::span(' ')));
        }

        return $tableColumns;
    }

    /**
     * @param Column $column
     * @return Element
     */
    protected function getColumnHeader(Column $column)
    {
        if ($column->isCheckable() && $this->grid->hasTool('bulk-edit')) {
            $input = Html::label([Html::checkbox()->addClass('js-bulk-edit-header-checkbox')
                ->setName('bulk-edit-column'), $column->getLabel(), ]);

            return Html::th($input)->addClass('bulk-check-column');
        }
        if ($column->isSortable()) {
            $link = Html::link($column->getLabel())
                ->addAttributes([
                    'href' => $this->grid->getColumnOrderUrl($column),
                ]);

            if (request('_order_by') === $column->getName()) {
                $link->append($this->getOrderByIcon());
            }

            return Html::th($link);
        }

        return Html::th(Html::span($column->getLabel()));
    }

    /**
     * @return Element
     */
    protected function getOrderByIcon()
    {
        return Html::i()
            ->addClass('fa')
            ->addClass(
                (request('_order') === 'DESC')
                    ? 'fa-sort-up'
                    : 'fa-sort-down'
            );
    }

    /**
     * @return Element
     */
    protected function tableHeader(): Element
    {
        $header = Html::header([
            Html::h1(trans('arbory::resources.all_resources')),
        ]);

        if ($this->grid->isPaginated()) {
            $header->append(Html::span(trans('arbory::pagination.items_found', ['total' => $this->items->total()]))
                ->addClass('extras totals only-text'));
        }

        return $header;
    }

    /**
     * @return Content
     */
    protected function table()
    {
        return new Content([
            $this->tableHeader(),
            Html::div(
                Html::table([
                    Html::thead(
                        Html::tr($this->getTableColumns()->toArray())
                    ),
                    Html::tbody(
                        $this->grid()->getRows()->map(function (Row $row) {
                            return $row->render();
                        })->toArray()
                    )->addClass('tbody'),
                ])->addClass('table')
            )->addClass('body'),
        ]);
    }

    /**
     * @return Link
     */
    protected function createButton()
    {
        if (! $this->grid->hasTool('create')) {
            return;
        }

        return
            Link::create($this->url('create'))
                ->asButton('primary')
                ->withIcon('add')
                ->title(trans('arbory::resources.create_new'));
    }

    /**
     * @return Element
     */
    protected function exportOptions()
    {
        $parameters = request()->all();

        return
            Html::div([
                Html::span(
                    trans('arbory::resources.export')
                )->addClass('title'),
                Html::div(
                    Link::create($this->url('export', $parameters + ['as' => 'xls']))
                        ->title('XLS')
                )->addClass('options'),
                Html::div(
                    Link::create($this->url('export', $parameters + ['as' => 'json']))
                        ->title('JSON')
                )->addClass('options'),
            ])->addClass('export');
    }

    /**
     * @return Tools
     */
    protected function footerTools()
    {
        $tools = new Tools();

        $tools->getBlock('secondary')->push($this->exportOptions());
        $tools->getBlock('primary')->push($this->createButton());

        $this->addCustomToolsToFooterToolset($tools);

        if ($this->grid->isPaginated() && $this->items->hasPages()) {
            $params = request()->only(['search', '_order', '_order_by', 'filter']);

            $this->items->appends($params);
            $pagination = (new Pagination($this->items))->render();
            $tools->getBlock($pagination->attributes()->get('class'))->push($pagination->content());
        }

        return $tools;
    }

    /**
     * @param Tools $toolset
     * @return void
     */
    protected function addCustomToolsToFooterToolset(Tools $toolset)
    {
        foreach ($this->grid->getTools() as [$tool, $location]) {
            $toolset->getBlock($location)->push($tool->render());
        }
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function footer()
    {
        $footer = new Footer('main');

        if ($this->grid->hasTools()) {
            $footer->getRows()->prepend($this->footerTools());
        }

        return $footer->render();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->grid()->getModule()->url($route, $parameters);
    }

    /**
     * @return Content
     */
    public function render()
    {
        $this->items = $this->grid->getItems();

        return new Content([
            Html::section([
                $this->bulkEdit(),
                $this->table(),
                $this->footer(),
            ])->addClass('content'),
            $this->filter(),
        ]);
    }
}
