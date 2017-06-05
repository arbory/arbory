<?php

namespace CubeSystems\Leaf\Services;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use CubeSystems\Leaf\Admin\Admin;
use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Module\ModuleRoutesRegistry;
use CubeSystems\Leaf\Admin\Widgets\Breadcrumbs;
use Illuminate\Database\Eloquent\Model;
use \LogicException;

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
     * @var ModuleRoutesRegistry
     */
    protected $routes;


    protected $breadcrumbs;


    /**
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
     * @param Sentinel $sentinel
     * @return bool
     */
    public function isAuthorized( Sentinel $sentinel )
    {
        $authorizationType = $this->configuration->getAuthorizationType();
dd( $authorizationType );
        switch( $authorizationType )
        {
            case Module::AUTHORIZATION_TYPE_NONE:

                $result = true;
                break;

            case Module::AUTHORIZATION_TYPE_ROLES:

                $result = false;

                foreach( $this->configuration->getAuthorizedRoles() as $authorizedRole )
                {
                    /** @noinspection PhpUndefinedMethodInspection */
                    if( $sentinel->inRole( $authorizedRole ) )
                    {
                        $result = true;
                        break;
                    }
                }

                break;

            default:
                throw new LogicException( 'Authorization type "' . $authorizationType . '" is not recognized' );
        }

        return $result;
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
     * @return AssetPipeline
     */
//    public function assets()
//    {
//        return $this->assets;
//    }

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
     * @return Route
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
