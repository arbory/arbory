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
     * @param mixed[] $items
     * @return Menu
     */
    public function build( array $items )
    {
        foreach( $items as &$item )
        {
            $item = $this->menuItemFactory->build( $item );
        }

        return new Menu( collect( $items ) );
    }
}
