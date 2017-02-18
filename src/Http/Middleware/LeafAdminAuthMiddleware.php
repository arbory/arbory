<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use CubeSystems\Leaf\Http\Controllers\Admin\CrudFrontController;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;
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
        return $next( $request );
        if( !$this->sentinel->check() )
        {
            return $this->denied( $request );
        }

        $targetModule = $this->resolveTargetModule( $request );

        if( !$targetModule )
        {
            throw new \RuntimeException( 'Could not find target module for route controller' );
        }

        if( !$targetModule->isAuthorized( $this->sentinel ) )
        {
            return $this->denied( $request );
        }

        return $next( $request );
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

    /**
     * @param Request $request
     * @return Module|null
     */
    private function resolveTargetModule( Request $request )
    {
        $routeController = $request->route()->getController();

        /* @var $modules ModuleRegistry */
        $modules = app( 'leaf.modules' );

        if( $routeController instanceof CrudFrontController )
        {
            $moduleName = $request->route()->getParameter( 'model' );

            $targetModule = $modules->findCrudModuleByName(
                $moduleName
            );
        }
        else
        {
            $targetModule = $modules->findModuleByControllerClass(
                '\\' . get_class( $routeController )
            );
        }

        return $targetModule;
    }
}
