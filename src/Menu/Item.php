<?php

namespace CubeSystems\Leaf\Menu;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Html\Elements;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Routing\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;

class Item extends AbstractItem
{
    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var array
     */
    protected $routeParams = [];

    /**
     * @var ModuleRegistry
     */
    protected $moduleRegistry;

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @param Sentinel $sentinel
     * @param ModuleRegistry $moduleRegistry
     * @param UrlGenerator $urlGenerator
     * @param Route $route
     */
    public function __construct(
        Sentinel $sentinel,
        ModuleRegistry $moduleRegistry,
        UrlGenerator $urlGenerator
//        Route $route
    )
    {
        $this->sentinel = $sentinel;
        $this->moduleRegistry = $moduleRegistry;
        $this->urlGenerator = $urlGenerator;
//        $this->route = $route;
        $this->children = new Collection();
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName( string $routeName )
    {
        $this->routeName = $routeName;
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * @param array $routeParams
     */
    public function setRouteParams( array $routeParams )
    {
        $this->routeParams = $routeParams;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule( Module $module )
    {
        $this->module = $module;
    }

    /**
     * @param Elements\Element $parentElement
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function render( Elements\Element $parentElement )
    {
//        $module = $this->moduleRegistry->findModuleByControllerClass( $this->module->getControllerClass() );

//        if( $module && $module->isAuthorized( $this->sentinel ) )
//        {
            $parentElement->append(
                Html::link([
                    Html::abbr( $this->getAbbreviation() )->addAttributes( [ 'title' => $this->getTitle() ] ),
                    Html::span( $this->getTitle() )->addClass( 'name' )
                ])
                    ->addClass( 'trigger ' . ( $this->isActive() ? 'active' : '' ) )
                    ->addAttributes( [ 'href' => $this->getUrl() ] )
            );

            return true;
//        }

        return false;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getUrl()
    {
        return $this->urlGenerator->route( $this->getRouteName(), $this->getRouteParams() );
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return false; // TODO:
        $activeController = ( new \ReflectionClass( $this->route->getController() ) )->getName();

        return $activeController === $this->module->getControllerClass();
    }
}
