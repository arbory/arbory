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

        $this->distributeMenuChildren( $menu );
        $this->distributeMenuPositions( $menu );

        return $menu;
    }

    /**
     * @param Menu $menu
     * @return void
     */
    protected function distributeMenuChildren( Menu $menu )
    {
        /** @var AbstractItem $menuItem */
        $menuItems = $menu->getItems();

        foreach( $menuItems as $menuItem )
        {
            if( $menuItem->hasModel() && $menuItem->getModel()->hasParent() )
            {
                $model = $menuItem->getModel();

                $parent = $menu->findItemByModelId( $model->getParentId() );

                $menuItem->setParent( $parent );

                $parent->addChild( $menuItem );

                $menu->removeItemByModelId( $model->getId() );
            }
        }
    }

    /**
     * @param Menu $menu
     * @return void
     */
    protected function distributeMenuPositions( Menu $menu )
    {
        /** @var AbstractItem $menuItem */
        $menuItems = $menu->flatten();

        foreach( $menuItems as $menuItem )
        {
            $model = $menuItem->getModel();

            if( $model && $model->isAfter() )
            {
                $children = $menu->getItems();

                if( $model->hasParent() )
                {
                    $parent = $menu->findItemByModelId( $model->getParentId() );
                    $children = $parent->getChildren();
                }

                $afterItem = $children->filter( function( AbstractItem $item ) use ( $model )
                {
                    return !$item->hasModel() ?: $item->getModel()->getId() === $model->getAfterId();
                } )->first();

                $currentPosition = $children->search( $menuItem );
                $afterKey = $children->search( $afterItem );

                $children->forget( $currentPosition );

                $children->splice( ++$afterKey, 0, [ $menuItem ] );
            }
        }
    }
}
