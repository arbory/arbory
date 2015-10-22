<?php

namespace CubeSystems\Leaf\Builders;

use CubeSystems\Leaf\Builders\Menu\Item;

/**
 * Class Menu
 * @package CubeSystems\Leaf\Builders
 */
class Menu
{
    /**
     * @var Item[]
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct( array $items = [ ] )
    {
        $this->addItems( $items );
    }

    /**
     * @param array $items
     */
    public function addItems( array $items = [ ] )
    {
        foreach( $items as $item )
        {
            $this->addItem( $item );
        }
    }

    /**
     * @param array $values
     */
    public function addItem( array $values = [ ] )
    {
        $this->items[] = new Item( $values );
    }

    /**
     * @return Item[]
     */
    public function items()
    {
        return $this->items;
    }
}
