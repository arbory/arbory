<?php

namespace Arbory\Base\Menu\Admin\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Nodes\MenuItem;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Illuminate\Contracts\Pagination\Paginator;

class Renderer
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Paginator
     */
    protected $page;

    /**
     * Renderer constructor.
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
     * @return Content
     */
    protected function table()
    {
        return new Content([
            Html::header([
                Html::h1(trans('arbory::resources.all_resources')),
                Html::span(trans('arbory::pagination.items_found', ['total' => $this->page->total()]))
                    ->addClass('extras totals only-text'),
            ]),
            Html::div(
                Html::div(
                    $this->buildTree($this->page->getCollection(), 1)
                )->addClass('collection')
            )->addClass('body'),
        ]);
    }

    /**
     * @param Collection $items
     * @param int $level
     * @return Element
     */
    protected function buildTree(Collection $items, $level = 1)
    {
        $url = $this->url('edit', '__ID__');

        $list = Html::ul()->addAttributes(['data-level' => $level]);

        $this->reorderItems($items);

        foreach ($items as $item) {
            $children = $item->children;
            $hasChildren = ($children && $children->count());

            $li = Html::li()
                ->addAttributes([
                    'data-level' => $level,
                    'data-id' => $item->getKey(),
                ]);

            $cell = Html::div()->addClass('node-cell active');

            $link = str_replace('__ID__', $item->getKey(), $url);

            foreach ($this->grid()->getColumns() as $column) {
                $cell->append(Html::link(
                    Html::span($item->{$column->getName()})
                )
                    ->addAttributes(['href' => $link]));
            }

            $li->append($cell);

            if ($hasChildren) {
                $li->append($this->buildTree($children, $level + 1));
            }

            if ($level === 1 && $item->hasParent()) {
                continue;
            }

            $list->append($li);
        }

        return $list;
    }

    /**
     * @param Collection $items
     * @return void
     */
    protected function reorderItems(Collection $items)
    {
        foreach ($items as $model) {
            /** @var MenuItem $model */
            if (! $model->isAfter()) {
                continue;
            }

            $afterItem = $items->filter(function (MenuItem $item) use ($model) {
                return $item->getId() === $model->getAfterId();
            })->first();

            $currentPosition = $items->search($model);
            $afterKey = $items->search($afterItem);

            $items->forget($currentPosition);

            $items->splice(++$afterKey, 0, [$model]);
        }
    }

    /**
     * @return Element
     */
    protected function footer()
    {
        $createButton = Link::create($this->url('create'))
            ->asButton('primary')
            ->withIcon('add')
            ->title(trans('arbory::resources.create_new'));

        $tools = new Tools();
        $tools->getBlock('primary')->push($createButton);

        $footer = new Footer('main');
        $footer->getRows()->prepend($tools);

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
     * @param Paginator $page
     * @return Element
     */
    public function render(Paginator $page)
    {
        $this->page = $page;

        return Html::section([
            $this->table(),
            $this->footer(),
        ]);
    }
}
