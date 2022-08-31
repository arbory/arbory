<?php

namespace Arbory\Base\Menu\Admin\Grid;

use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Arbory\Base\Nodes\MenuItem;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Renderer
{
    /**
     * @var Paginator
     */
    protected $page;

    /**
     * Renderer constructor.
     */
    public function __construct(protected Grid $grid)
    {
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

    protected function buildTree(Collection $items, int $level = 1): Element
    {
        $url = $this->url('edit', ['__ID__']);

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
     * @return void
     */
    protected function reorderItems(Collection $items)
    {
        foreach ($items as $model) {
            /** @var Model $model */
            if (!$model->isAfter()) {
                continue;
            }

            $afterItem = $items->filter(fn(Model $item) => $item->getId() === $model->getAfterId())->first();

            $currentPosition = $items->search($model);
            $afterKey = $items->search($afterItem);

            $items->forget($currentPosition);

            $items->splice(++$afterKey, 0, [$model]);
        }
    }

    protected function footer(): Element
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

    public function url(string $route, array $parameters = []): string
    {
        return $this->grid()->getModule()->url($route, $parameters);
    }

    public function render(Paginator $page): Element
    {
        $this->page = $page;

        return Html::section([
            $this->table(),
            $this->footer(),
        ]);
    }
}
