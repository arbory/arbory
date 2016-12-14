<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use CubeSystems\Leaf\Http\Controllers\Admin\ResourceController;
use CubeSystems\Leaf\Menu\Item;
use CubeSystems\Leaf\Menu\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class AdminMiddleware
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminAuthMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * LeafAdminAuthMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if( !$this->sentinel->check() )
        {
            return $this->denied( $request );
        }

        $controller = $request->route()->getController();

        if( $controller instanceof ResourceController )
        {
            $slug = $request->route()->getParameter( 'model' );

            $controllerClass = $controller->findControllerBySlug( $slug );
        }
        else
        {
            $controllerClass = '\\' . get_class( $controller );
        }

        /* @var $menu Menu */
        $menu = app( 'leaf.menu' );

        $menuItem = $menu->findItemByController( $controllerClass );

        if( !$menuItem )
        {
            throw new \RuntimeException( 'Could not find menu item for controller' );
        }

        if( !$this->userHasMatchingRole( $menuItem ) )
        {
            return $this->denied( $request );
        }

        return $next( $request );
    }

    /**
     * @param Item $menuItem
     * @return bool
     */
    private function userHasMatchingRole( Item $menuItem )
    {
        $authorized = false;

        if( count( $menuItem->getAllowedRoles() ) )
        {
            foreach( $menuItem->getAllowedRoles() as $role )
            {
                /** @noinspection PhpUndefinedMethodInspection */
                if( $this->sentinel->inRole( $role ) )
                {
                    $authorized = true;
                    break;
                }
            }
        }
        else
        {
            $authorized = true;
        }

        return $authorized;
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    private function denied( Request $request )
    {
        $message = 'Unauthorized';

        if( $request->ajax() )
        {
            $result = response()->json( [ 'error' => $message ], 401 );
        }
        else
        {
            session()->flash( 'error', $message );

            $result = redirect()->guest( route( 'admin.login.form' ) );
        }

        return $result;
    }
}
