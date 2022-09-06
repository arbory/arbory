<?php

namespace Arbory\Base\Nodes\Admin\Grid;

use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Support\Nodes\NameGenerator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Renderer.
 */
class Renderer implements Renderable
{
    public const COOKIE_NAME_NODES = 'nodes';

    /**
     * @var Paginator
     */
    protected Paginator $page;

    /**
     * Renderer constructor.
     */
    public function __construct(protected Grid $grid)
    {
    }

    public function grid(): Grid
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

        $collapser = Html::div(
            Html::button(Html::i('keyboard_arrow_right')->addClass('mt-icon'))
                ->addClass('button only-icon secondary collapser trigger')
                ->addAttributes(['type' => 'button'])
        )->addClass('collapser-cell');

        $items = $items->sortBy('lft');

        foreach ($items as $item) {
            /**
             * @var Node
             */
            $collapsed = $this->getNodeCookie($item->getKey());
            $children = $item->children;
            $hasChildren = ($children && $children->count());

            $li = Html::li()
                ->addAttributes([
                    'data-level' => $level,
                    'data-id' => $item->getKey(),
                ])
                ->addClass((($hasChildren) ? 'has-children' : null));

            if ($collapsed) {
                $li->addClass('collapsed');
            }

            $li->append(
                Toolbox::create($this->url('dialog', ['dialog' => 'toolbox', 'id' => $item->getKey()]))->render()
            );

            if ($hasChildren) {
                $li->append($collapser);
            }

            $cell = Html::div()->addClass('node-cell ' . ($item->isPublic() ? 'active' : ''));

            $link = str_replace('__ID__', $item->getKey(), $url);

            foreach ($this->grid()->getColumns() as $column) {
                $cellValue = $item->{$column->getName()};

                $cell->append(
                    Html::link(
                        Html::span($cellValue)
                    )
                        ->append(
                            Html::span($this->makeNameFromType($item->getContentType()))->addClass('content-type')
                        )
                        ->addClass('trigger')
                        ->addAttributes(['href' => $link])
                );
            }

            $li->append($cell);

            if ($hasChildren) {
                $li->append($this->buildTree($children, $level + 1));
            }

            $list->append($li);
        }

        return $list;
    }

    protected function footer(): Element
    {
        $createButton = Link::create($this->url('dialog', ['content_types']))
            ->asButton('primary ajaxbox')
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

    public function render(): Element
    {
        $this->page = $this->grid->getItems();

        return Html::section([
            $this->table(),
            $this->footer(),
        ]);
    }

    protected function getNodeCookie(string $nodeId)
    {
        $cookie = (array) json_decode(Arr::get($_COOKIE, self::COOKIE_NAME_NODES));

        return Arr::get($cookie, $nodeId, true);
    }

    protected function makeNameFromType(string $type): string
    {
        return app(NameGenerator::class)->generate($type);
    }
}
