<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use Cartalyst\Sentinel\Roles\IlluminateRoleRepository;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var IlluminateRoleRepository
     */
    protected $roleRepository;

    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * RoleController constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->roleRepository = app()->make( 'sentinel.roles' );
        $this->sentinel = $sentinel;
    }

    /**
     * @return View
     */
    public function index()
    {
        $roles = $this->roleRepository->createModel()->all();

        return view( 'leaf::controllers.roles.index' )
            ->with( 'roles', $roles );
    }

    /**
     * @return View
     */
    public function create()
    {
        return view( 'leaf::controllers.roles.create' );
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function store( Request $request )
    {
        $this->validate( $request, [
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:roles',
        ] );

        $role = $this->sentinel->getRoleRepository()->createModel()->create( [
            'name' => trim( $request->get( 'name' ) ),
            'slug' => trim( $request->get( 'slug' ) ),
        ] );

        $permissions = [];
        foreach( $request->get( 'permissions', [] ) as $permission => $value )
        {
            $permissions[$permission] = (bool) $value;
        }

        $role->permissions = $permissions;
        $role->save();

        if( $request->ajax() )
        {
            return response()->json( [ 'role' => $role ], 200 );
        }

        session()->flash( 'success', "Role '{$role->name}' has been created." );

        return redirect( route( 'admin.roles.index' ) );
    }

    /**
     * @return Response
     */
    public function show()
    {
        return redirect( route( 'admin.roles.index' ) );
    }

    /**
     * @param $id
     * @return Response|View
     */
    public function edit( $id )
    {
        $role = $this->roleRepository->findById( $id );

        if( $role )
        {
            return view( 'leaf::controllers.roles.edit' )
                ->with( 'role', $role );
        }

        session()->flash( 'error', 'Invalid role.' );

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update( Request $request, $id )
    {
        $this->validate( $request, [
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:roles,slug,' . $id,
        ] );

        $role = $this->roleRepository->findById( $id );
        if( !$role )
        {
            if( $request->ajax() )
            {
                return response()->json( 'Invalid role.', 422 );
            }
            session()->flash( 'error', 'Invalid role.' );

            return redirect()->back()->withInput();
        }

        $role->name = $request->get( 'name' );
        $role->slug = $request->get( 'slug' );

        $permissions = [];
        foreach( $request->get( 'permissions', [] ) as $permission => $value )
        {
            $permissions[$permission] = (bool) $value;
        }

        $role->permissions = $permissions;
        $role->save();

        if( $request->ajax() )
        {
            return response()->json( [ 'role' => $role ], 200 );
        }

        session()->flash( 'success', "Role '{$role->name}' has been updated." );

        return redirect( route( 'admin.roles.index' ) );
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function destroy( Request $request, $id )
    {
        $role = $this->roleRepository->findById( $id );

        $role->delete();

        $message = "Role '{$role->name}' has been removed.";
        if( $request->ajax() )
        {
            return response()->json( [ $message ], 200 );
        }

        session()->flash( 'success', $message );

        return redirect( route( 'admin.roles.index' ) );
    }
}
