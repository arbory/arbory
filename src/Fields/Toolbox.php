<?php

namespace CubeSystems\Leaf\Fields;
use CubeSystems\Leaf\Fields\Toolbox\Item;

/**
 * Class Toolbox
 * @package CubeSystems\Leaf\Fields
 */
class Toolbox
{
    protected $items;

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $name
     * @return Item
     */
    public function addItem( $name )
    {
        $item = new Item( $name );
        $item->setToolbox( $this );

        $this->items[] = $item;

        return $item;
    }

    public function renderMenu()
    {
        return view( 'leaf::builder.fields.toolbox_menu', [
            'items' => $this->getItems(),
        ] );
    }
}
