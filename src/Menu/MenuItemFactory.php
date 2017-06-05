<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Admin\Admin;
use CubeSystems\Leaf\Admin\Module\OLDRoute;
use CubeSystems\Leaf\Nodes\MenuItem;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

class MenuItemFactory
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @param Admin $admin
     */
    public function __construct(
        Admin $admin
    )
    {
        $this->admin = $admin;
    }

    /**
     * @param string|string[] $definition
     * @return AbstractItem
     */
    public function build( $definition ): AbstractItem
    {
        $menuItem = \App::make( Item::class );

        if( is_array( $definition ) )
        {
            $menuItem = new Group();

            foreach( $definition as $item )
            {
                $menuItem->addChild( $this->build( $item ) );
            }
        }

        $menuItem->setTitle( $this->getMenuItemName( $definition ) );
//        $menuItem->setModel( $this->admin->modules()->findModuleByControllerClass(
//            $this->getMenuItemControllerClass( $definition )
//        ) );

        //        if( $model->hasModule() )
//        {
//            // $this->admin->modules()->findModuleByControllerClass( $controllerClass);
//
//            $item = $this->container->make( Item::class );
//
//            $module = $this->moduleRegistry->findModuleByControllerClass( $moduleName );
//
//            // TODO: better method to get slug
//            $item->setRouteName( sprintf( 'admin.%s.index', $module->name() ) );
////            $item->setModule( $module );
//        }
//        else
//        {
//            $item = $this->container->make( Group::class );
//        }

        /** @var AbstractItem $item */
        // $item->setTitle( $model->getTitle() );
        // $item->setModel( $model );

        return $menuItem;
    }

    /**
     * @param array|string $definition
     * @return string
     */
    protected function getMenuItemControllerClass( $definition ): string
    {
        return is_array( $definition ) ? $definition[0] : $definition;
    }

    /**
     * @param array|string $definition
     * @return string
     */
    protected function getMenuItemName( $definition ): string
    {
        return Str::words( class_basename( $this->getMenuItemControllerClass( $definition ) ) );
    }
}
