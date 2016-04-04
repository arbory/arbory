<?php

namespace CubeSystems\Leaf\Fields;
use CubeSystems\Leaf\Fields\Toolbox\Item;

/**
 * Class Toolbox
 * @package CubeSystems\Leaf\Fields
 */
class Toolbox extends AbstractField
{
    protected $items;

    /**
     * @return bool
     */
    public function hasBefore()
    {
        return true;
    }

    /**
     * @return \Closure
     */
    public function getBefore()
    {
        return function ()
        {
            return null;
        };
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

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

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view( $this->getViewName(), [
            'toolbox_url' => route( 'admin.model.dialog', [
                'model' => $this->getFieldSet()->getController()->getSlug(),
                'dialog' => 'toolbox',
                'name' => $this->getName(),
                'id' => $this->getRow()->getIdentifier(),
            ] ),
        ] );
    }

    public function renderMenu()
    {
        return view( $this->getViewName() . '_menu', [
            'items' => $this->getItems(),
        ] );
    }
}
