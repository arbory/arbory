<?php

namespace CubeSystems\Leaf\Menu;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Services\ModuleRegistry;

/**
 * Class CrudModuleItem
 * @package CubeSystems\Leaf\Menu
 */
class CrudModuleItem extends ModuleItem
{
    /**
     * ModuleItem constructor.
     * @param array $values
     * @param Sentinel $sentinel
     * @param ModuleRegistry $modules
     */
    public function __construct( array $values = [], Sentinel $sentinel, ModuleRegistry $modules )
    {
        parent::__construct( $values, $sentinel, $modules );

        $this->setRouteName( 'admin.model.index' );
        $this->setRouteParams( [ 'model' => $this->module->getName() ] );
    }
}