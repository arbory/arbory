<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Roles\Role;

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
}
