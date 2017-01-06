<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use Cartalyst\Sentinel\Roles\IlluminateRoleRepository;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Roles\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends AbstractCrudController
{
    protected $resource = Role::class;

    /**
     * @param FieldSet $fieldSet
     */
    public function indexFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Text( 'name' ) );
        $fieldSet->add( new Text( 'created_at' ) );
        $fieldSet->add( new Text( 'updated_at' ) );
        $fieldSet->add( new Toolbox( 'toolbox' ) );
    }

    /**
     * @param FieldSet $fieldSet
     */
    public function formFields( FieldSet $fieldSet )
    {
        $permissions = [
            'users.create',
            'users.update',
            'users.view',
            'users.destroy',
            'roles.create',
            'roles.update',
            'roles.view',
            'roles.delete',
        ];

        $fieldSet->add( new Text( 'name' ) );

    }

//
//    /**
//     * @param  Request $request
//     * @return Response
//     */
//    public function store( Request $request )
//    {
//        $this->validate( $request, [
//            'name' => 'required',
//            'slug' => 'required|alpha_dash|unique:roles',
//        ] );
//
//        $role = Sentinel::getRoleRepository()->createModel()->create( [
//            'name' => trim( $request->get( 'name' ) ),
//            'slug' => trim( $request->get( 'slug' ) ),
//        ] );
//
//        $permissions = [];
//        foreach( $request->get( 'permissions', [] ) as $permission => $value )
//        {
//            $permissions[$permission] = (bool) $value;
//        }
//
//        $role->permissions = $permissions;
//        $role->save();
//
//        if( $request->ajax() )
//        {
//            return response()->json( [ 'role' => $role ], 200 );
//        }
//
//        session()->flash( 'success', "Role '{$role->name}' has been created." );
//
//        return redirect( route( 'admin.roles.index' ) );
//    }
//
//
//
//    /**
//     * @param Request $request
//     * @param $id
//     * @return RedirectResponse
//     */
//    public function update( Request $request, $id )
//    {
//        $this->validate( $request, [
//            'name' => 'required',
//            'slug' => 'required|alpha_dash|unique:roles,slug,' . $id,
//        ] );
//
//        $role = $this->roleRepository->findById( $id );
//        if( !$role )
//        {
//            if( $request->ajax() )
//            {
//                return response()->json( 'Invalid role.', 422 );
//            }
//            session()->flash( 'error', 'Invalid role.' );
//
//            return redirect()->back()->withInput();
//        }
//
//        $role->name = $request->get( 'name' );
//        $role->slug = $request->get( 'slug' );
//
//        $permissions = [];
//        foreach( $request->get( 'permissions', [] ) as $permission => $value )
//        {
//            $permissions[$permission] = (bool) $value;
//        }
//
//        $role->permissions = $permissions;
//        $role->save();
//
//        if( $request->ajax() )
//        {
//            return response()->json( [ 'role' => $role ], 200 );
//        }
//
//        session()->flash( 'success', "Role '{$role->name}' has been updated." );
//
//        return redirect( route( 'admin.roles.index' ) );
//    }

}
