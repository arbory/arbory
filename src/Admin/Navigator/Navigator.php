<?php

namespace Arbory\Base\Admin\Navigator;

use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Renderable;

class Navigator implements Renderable
{
    /**
     * @var Item[]|Collection
     */
    protected $items;

    public function __construct()
    {
        // TODO: Maybe start from item root?
        $this->items = new Collection();
    }

    public function add(Item $item)
    {
        $this->items->push($item);
    }

    /**
     * @param  NavigableInterface  $navigable
     * @param                    $title
     * @param  null  $anchor
     *
     * @return Item
     */
    public function addItem(NavigableInterface $navigable, $title, $anchor = null): Item
    {
        $item = new Item($navigable, $title, $anchor);
        $this->add($item);

        return $item;
    }

    /**
     * @return Item[]|Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $list = Html::ul()->addClass('navigator');

        foreach ($this->getItems() as $item) {
            $list->append($item->render());
        }

        return $list;
    }
}
