<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Closure;
use CubeSystems\Leaf\Menu\Item;
use CubeSystems\Leaf\Menu\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class AdminMiddleware
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if( !Sentinel::check() )
        {
            return $this->denied( $request );
        }

//        $controllerClass = '\\' . get_class( $request->route()->getController() );
//
//        /* @var $menu Menu */
//        $menu = app( 'leaf.menu' );
//
//        $menuItem = $menu->findItemByController( $controllerClass );
//
//        if( !$menuItem )
//        {
//            throw new \RuntimeException( 'Could not find menu item for controller' );
//        }
//
//        if( !$this->userHasMatchingRole( $menuItem ) )
//        {
//            return $this->denied( $request );
//        }

        return $next( $request );
    }

    /**
     * @param Item $menuItem
     * @return bool
     */
    private function userHasMatchingRole( Item $menuItem )
    {
        $authorized = false;

        foreach( $menuItem->getAllowedRoles() as $role )
        {
            /** @noinspection PhpUndefinedMethodInspection */
            if( Sentinel::inRole( $role ) )
            {
                $authorized = true;
                break;
            }
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
