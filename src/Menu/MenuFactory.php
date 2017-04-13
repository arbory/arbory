<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Nodes\MenuItem;
use Illuminate\Support\Collection;

class MenuFactory
{
    /**
     * @var MenuItemFactory
     */
    protected $menuItemFactory;

    /**
     * @param MenuItemFactory $menuItemFactory
     */
    public function __construct( MenuItemFactory $menuItemFactory )
    {
        $this->menuItemFactory = $menuItemFactory;
    }

    /**
     * @param Collection $models
     * @return Menu
     */
    public function build( Collection $models )
    {
        $items = $models->transform( function( MenuItem $model )
        {
            return $this->menuItemFactory->build( $model );
        } );

        $menu = new Menu( $items );

        $this->distributeMenuChildren( $menu, $menu->getItems() );

        return $menu;
    }

    /**
     * @param Menu $menu
     * @param Collection $menuItems
     * @return void
     */
    protected function distributeMenuChildren( Menu $menu, Collection $menuItems )
    {
        /** @var AbstractItem $menuItem */
        foreach( $menuItems as $menuItem )
        {
            if( $menuItem->hasModel() && $menuItem->getModel()->hasParent() )
            {
                $model = $menuItem->getModel();

                $parent = $menu->findItemByModelId( $model->getParent() );

                $parent->addChild( $menuItem );

                $menu->removeItemByModelId( $model->getId() );
            }
        }
    }
}