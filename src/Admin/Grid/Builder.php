<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Filter\Renderer as FilterRenderer;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Widgets\Pagination;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\CheckBox;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class Builder.
 */
class Builder implements Renderable
{
    /**
     * @var Collection|LengthAwarePaginator
     */
    protected $items;

    /**
     * Builder constructor.
     */
    public function __construct(protected Grid $grid)
    {
    }

    public function grid(): Grid
    {
        return $this->grid;
    }

    protected function bulkEdit(): ?Element
    {
        if (!$this->grid->hasTool('bulk-edit')) {
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

    protected function addBulkColumn(): Column
    {
        return $this->grid->prependColumn('id', trans('arbory::resources.nr'))
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

    protected function filter(): Content|string|null
    {
        if (!$this->grid->hasTool('filter')) {
            return null;
        }

        $filterManager = $this->grid->getFilterManager();

        return (new FilterRenderer())
            ->setManager($filterManager)
            ->render();
    }

    protected function getTableColumns(): Collection
    {
        $tableColumns = $this->grid()->getColumns()->map(fn(Column $column) => $this->getColumnHeader($column));

        if ($this->grid->isToolboxEnable()) {
            $tableColumns->push(Html::th(Html::span(' ')));
        }

        return $tableColumns;
    }

    protected function getColumnHeader(Column $column): Element
    {
        if ($column->isCheckable() && $this->grid->hasTool('bulk-edit')) {
            $input = Html::label([Html::checkbox()->addClass('js-bulk-edit-header-checkbox')
                ->setName('bulk-edit-column'), $column->getLabel(),]);

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

    protected function getOrderByIcon(): Element
    {
        return Html::i()
            ->addClass('fa')
            ->addClass(
                (request('_order') === 'DESC')
                    ? 'fa-sort-up'
                    : 'fa-sort-down'
            );
    }

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

    protected function table(): Content
    {
        return new Content([
            $this->tableHeader(),
            Html::div(
                Html::table([
                    Html::thead(
                        Html::tr($this->getTableColumns()->toArray())
                    ),
                    Html::tbody(
                        $this->grid()->getRows()->map(fn(Row $row) => $row->render())->toArray()
                    )->addClass('tbody'),
                ])->addClass('table')
            )->addClass('body'),
        ]);
    }

    protected function createButton(): ?Link
    {
        if (!$this->grid->hasTool('create')) {
            return null;
        }

        return
            Link::create($this->url('create'))
                ->asButton('primary')
                ->withIcon('add')
                ->title(trans('arbory::resources.create_new'));
    }

    protected function exportOptions(): Element
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

    protected function footerTools(): Tools
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

    protected function addCustomToolsToFooterToolset(Tools $toolset): void
    {
        foreach ($this->grid->getTools() as [$tool, $location]) {
            $toolset->getBlock($location)->push($tool->render());
        }
    }

    protected function footer(): Element
    {
        $footer = new Footer('main');

        if ($this->grid->hasTools()) {
            $footer->getRows()->prepend($this->footerTools());
        }

        return $footer->render();
    }

    public function url(string $route, array $parameters = []): string
    {
        return $this->grid()->getModule()->url($route, $parameters);
    }

    public function render(): Content
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
