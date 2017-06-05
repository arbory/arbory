<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Admin\Admin;

class MenuItemFactory
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @param Admin $admin
     */
    public function __construct( Admin $admin )
    {
        $this->admin = $admin;
    }

    /**
     * @param array|string $definition
     * @return AbstractItem
     * @throws \DomainException
     */
    public function build( $definition ): AbstractItem
    {
        $menuItem = null;

        if( is_array( $definition ) )
        {
            $menuItem = new Group();

            foreach( $definition as $item )
            {
                $menuItem->addChild( $this->build( $item ) );
            }
        }
        else
        {
            $module = $this->admin->modules()->findModuleByControllerClass( $definition );

            if( !$module )
            {
                throw new \DomainException( sprintf( 'No controller found for [%s] module ', $definition ) );
            }

            $menuItem = new Item( $this->admin, $module );
        }

        $menuItem->setTitle( $this->getMenuItemName( $definition ) );

        return $menuItem;
    }

    /**
     * @param array|string $definition
     * @return string
     */
    protected function getMenuItemName( $definition ): string
    {
        $name = is_array( $definition ) ? $definition[ 0 ] : $definition;
        $name = str_replace( [ '_', 'controller' ], ' ', snake_case( class_basename( $name ) ) );

        return title_case( $name );
    }
}
