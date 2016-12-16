<?php

namespace CubeSystems\Leaf\Menu;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;

/**
 * Class ModuleItem
 * @package CubeSystems\Leaf\Menu
 */
class ModuleItem extends AbstractItem
{
    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var array
     */
    protected $routeParams;

    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * @var Module
     */
    protected $module;

    /**
     * ModuleItem constructor.
     * @param array $values
     * @param Sentinel $sentinel
     * @param ModuleRegistry $modules
     */
    public function __construct( array $values = [], Sentinel $sentinel, ModuleRegistry $modules )
    {
        parent::__construct( $values );

        $this->setRouteName( array_get( $values, 'route_name' ) );
        $this->setRouteParams( array_get( $values, 'route_params', [] ) );

        $this->sentinel = $sentinel;

        $moduleName = array_get( $values, 'module_name' );
        $module = $modules->findModuleByName( $moduleName );

        if( !$module )
        {
            throw new \LogicException( 'Module named "' . $moduleName . '" not registered.' );
        }

        $this->module = $module;
    }

    /**
     * @param string $routeName
     * @return $this
     */
    public function setRouteName( $routeName )
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * @param array $routeParams
     * @return $this
     */
    protected function setRouteParams( array $routeParams = [] )
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->module->getName();
    }

    /**
     * @return mixed
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * @param Elements\Element $parentElement
     * @return bool
     */
    public function render( Elements\Element $parentElement )
    {
        /* @var $modules ModuleRegistry */
        $modules = app( 'leaf.modules' );

        $module = $modules->findModuleByName( $this->getModuleName() );

        if( $module && $module->isAuthorized( $this->sentinel ) )
        {
            $parentElement->append(
                ( new Elements\A() )
                    ->addClass( 'trigger' )
                    ->setAttributeValue( 'href', $this->getUrl() )
                    ->append( ( new Elements\Abr( $this->getAbbreviation() ) )
                        ->setAttributeValue( 'title', $this->getTitle() ) )
                    ->append(
                        ( new Elements\Span( $this->getTitle() ) )
                            ->addClass( 'name' ) )
            );

            $result = true;
        }
        else
        {
            $result = false;
        }

        return $result;
    }

    /**
     * @return string
     */
    protected
    function getUrl()
    {
        return route( $this->getRouteName(), $this->getRouteParams() );
    }
}
