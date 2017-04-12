<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\BelongsToMany;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Nodes\MenuItem;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class MenuBuilderController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = MenuItem::class;

    /**
     * @var ModuleRegistry
     */
    protected $moduleRegistry;

    public function __construct( ModuleRegistry $moduleRegistry )
    {
        $this->moduleRegistry = $moduleRegistry;
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        $modules = $this->getModuleOptions();
        $menuItems = $this->getMenuItemOptions();

        $form = $this->module()->form( $model, function( Form $form ) use ( $menuItems, $modules )
        {
            $form->addField( new Text( 'title' ) );
            $form->addField( new Form\Fields\Dropdown( 'parent', $menuItems ) );
            $form->addField( new Form\Fields\Dropdown( 'controller', $modules ) );
            $form->addField( new BelongsToMany( 'roles' ) );
        } );

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->module()->grid( $this->resource(), function( Grid $grid )
        {
            $grid->column( 'title' );
            $grid->column( 'controller' );
        } );
    }

    /**
     * @return Form\Fields\DropdownOption[]
     */
    protected function getModuleOptions()
    {
        $modules = array_map( function( $module )
        {
            /** @var Module $module */
            return new Form\Fields\DropdownOption( $module->getControllerClass(), $module->getName() );
        }, $this->moduleRegistry->getModulesByControllerClass() );

        array_unshift( $modules, new Form\Fields\DropdownOption( null, '' ) );

        return $modules;
    }

    /**
     * @return Form\Fields\DropdownOption[]
     */
    protected function getMenuItemOptions()
    {
        return MenuItem::all()->transform( function( $item )
        {
            /** @var MenuItem $item */
            return new Form\Fields\DropdownOption( $item->getId(), $item->getTitle() );
        } )
        ->prepend( new Form\Fields\DropdownOption( null, '' ) )
        ->toArray();
    }
}