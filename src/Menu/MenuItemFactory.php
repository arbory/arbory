<?php

namespace CubeSystems\Leaf\Menu;

use CubeSystems\Leaf\Admin\Module\Route;
use CubeSystems\Leaf\Nodes\MenuItem;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Container\Container;

class MenuItemFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ModuleRegistry
     */
    protected $moduleRegistry;

    /**
     * @param Container $container
     * @param ModuleRegistry $moduleRegistry
     */
    public function __construct(
        Container $container,
        ModuleRegistry $moduleRegistry
    )
    {
        $this->container = $container;
        $this->moduleRegistry = $moduleRegistry;
    }

    /**
     * @param MenuItem $model
     * @return AbstractItem
     */
    public function build( MenuItem $model ): AbstractItem
    {
        $item = null;

        if( $model->hasModule() )
        {
            $item = $this->container->make( Item::class );

            $moduleName = $model->getModule();
            $module = $this->moduleRegistry->findModuleByControllerClass( $moduleName );

            // TODO: better method to get slug
            $item->setRouteName( sprintf( 'admin.%s.index', Route::generateSlugFromClassName( $moduleName ) ) );
            $item->setModule( $module );
        }
        else
        {
            $item = $this->container->make( Group::class );
        }

        /** @var AbstractItem $item */
        $item->setTitle( $model->getTitle() );
        $item->setAbbreviation( $model->getTitle() );
        $item->setModel( $model );

        return $item;
    }
}