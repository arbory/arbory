<?php

namespace CubeSystems\Leaf\Admin;

use Closure;
use CubeSystems\Leaf\Admin\Module\ResourceRoutes;
use CubeSystems\Leaf\Admin\Widgets\Breadcrumbs;
use CubeSystems\Leaf\Auth\Roles\Role;
use CubeSystems\Leaf\Services\ModuleConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Module
 * @package CubeSystems\Leaf\Services
 */
class Module
{
    const AUTHORIZATION_TYPE_ROLES = 'roles';
    const AUTHORIZATION_TYPE_PERMISSIONS = 'permissions';
    const AUTHORIZATION_TYPE_NONE = 'none';

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var ModuleConfiguration
     */
    private $configuration;

    /**
     * @var ResourceRoutes
     */
    protected $routes;


    protected $breadcrumbs;


    /**
     * @param Admin $admin
     * @param ModuleConfiguration $configuration
     */
    public function __construct( Admin $admin, ModuleConfiguration $configuration )
    {
        $this->admin = $admin;
        $this->configuration = $configuration;
    }

    public function __toString()
    {
        return $this->name();
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->configuration->getControllerClass();
    }

    /**
     * @return ModuleConfiguration
     */
    public function getConfiguration(): ModuleConfiguration
    {
        return $this->configuration;
    }

    /**
     * @return bool
     */
    public function isAuthorized( )
    {
        if( !$this->admin->sentinel()->check() )
        {
            return false;
        }

        /**
         * @var $roles Role[]|Collection
         */
        $roles = $this->admin->sentinel()->getUser()->roles;

        $permissions = $roles->mapWithKeys(function( Role $role ){
            return $role->getPermissions();
        })->toArray();

        return in_array( $this->getControllerClass(), $permissions, true );
    }

    /**
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        if( $this->breadcrumbs === null )
        {
            $this->breadcrumbs = new Breadcrumbs();  // TODO: Move this to menu
            $this->breadcrumbs->addItem( $this->name(), $this->url( 'index' ) );
        }

        return $this->breadcrumbs;
    }

    /**
     * @param Model $model
     * @param Closure $closure
     * @return Form
     */
    public function form( Model $model, Closure $closure )
    {
        $form = new Form( $model, $closure );
        $form->setModule( $this );

        return $form;
    }

    /**
     * @param Model $model
     * @param Closure $builder
     * @return Grid
     */
    public function grid( Model $model, Closure $builder )
    {
        $grid = new Grid( $model, $builder );
        $grid->setModule( $this );

        return $grid;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->getConfiguration()->getName();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        if( $this->routes === null)
        {
            $this->routes = $this->admin->routes()->findByModule( $this );
        }

        return $this->routes->getUrl( $route, $parameters );
    }

}
