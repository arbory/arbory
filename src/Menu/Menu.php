<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;

/**
 * Class Menu
 * @package CubeSystems\Leaf\Menu
 */
class Menu
{
    /**
     * @var AbstractItem[]
     */
    protected $items;

    /**
     * @param array $itemsConfigurationArray
     */
    public function __construct( array $itemsConfigurationArray = [] )
    {
        $this->addItems( $itemsConfigurationArray );
    }

    /**
     * @param array $itemsConfigurationArray
     */
    public function addItems( array $itemsConfigurationArray = [] )
    {
        foreach( $itemsConfigurationArray as $itemConfigurationArray )
        {
            $this->addItem( $itemConfigurationArray );
        }
    }

    /**
     * @param array $itemConfigurationArray
     */
    public function addItem( array $itemConfigurationArray = [] )
    {
        $this->items[] = AbstractItem::make( $itemConfigurationArray );
    }

    /**
     * @return AbstractItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Elements\Element
     */
    public function render()
    {
        $list = Html::ul()->addClass( 'block' );

        foreach( $this->getItems() as $item )
        {
            $li = Html::li( )
                ->addAttributes( [ 'data-name' => '' ] );

            if( $item->render( $li ) )
            {
                $list->append( $li );
            }
        }

        return $list;
    }
}
