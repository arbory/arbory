<?php

namespace Arbory\Base\Nodes\Admin\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Nodes\Node;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Support\Nodes\NameGenerator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Class Renderer.
 */
class Renderer implements Renderable
{
    const COOKIE_NAME_NODES = 'nodes';

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

            $cell = Html::div()->addClass('node-cell '.($item->isActive() ? 'active' : ''));

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

    /**
     * @return Element
     */
    protected function footer()
    {
        $createButton = Link::create($this->url('dialog', 'content_types'))
            ->asButton('primary ajaxbox')
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
     * @return Element
     */
    public function render()
    {
        $this->page = $this->grid->getItems();

        return Html::section([
            $this->table(),
            $this->footer(),
        ]);
    }

    /**
     * @param string $nodeId
     * @return bool
     */
    protected function getNodeCookie($nodeId)
    {
        $cookie = (array) json_decode(Arr::get($_COOKIE, self::COOKIE_NAME_NODES));

        return Arr::get($cookie, $nodeId, true);
    }

    /**
     * @param string $type
     * @return string
     */
    protected function makeNameFromType($type): string
    {
        return app(NameGenerator::class)->generate($type);
    }
}
