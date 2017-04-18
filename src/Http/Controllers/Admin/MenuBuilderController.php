<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\BelongsToMany;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Layout;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Menu\Admin\Grid\Renderer;
use CubeSystems\Leaf\Menu\MenuFactory;
use CubeSystems\Leaf\Nodes\MenuItem;
use CubeSystems\Leaf\Services\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ReflectionClass;
use Illuminate\Contracts\View\Factory as ViewFactory;

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

    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * @var MenuFactory
     */
    protected $menuFactory;

    /**
     * @param ModuleRegistry $moduleRegistry
     * @param ViewFactory $viewFactory
     * @param MenuFactory $menuFactory
     */
    public function __construct(
        ModuleRegistry $moduleRegistry,
        ViewFactory $viewFactory,
        MenuFactory $menuFactory
    )
    {
        $this->moduleRegistry = $moduleRegistry;
        $this->viewFactory = $viewFactory;
        $this->menuFactory = $menuFactory;
    }

    /**
     * @param MenuItem $model
     * @return Form
     */
    protected function form( MenuItem $model )
    {
        $modules = $this->getModuleOptions();
        $menuItems = $this->getMenuItemOptions();

        $form = $this->module()->form( $model, function( Form $form ) use ( $model, $menuItems, $modules )
        {
            $moduleName = $model->getModule();

            if( $moduleName )
            {
                $moduleName = new ReflectionClass( $moduleName );
                $moduleName = $moduleName->getShortName();
            }

            $form->addField( new Text( 'title' ) );

            $form->addField(
                new Form\Fields\Dropdown( 'after_id', $menuItems, $model->getAfterId() ?: 0 )
            );

            $form->addField(
                new Form\Fields\Dropdown( 'parent_id', $menuItems, $model->getParentId() ?: 0 )
            );

            $form->addField(
                new Form\Fields\Dropdown( 'module', $modules, array_search( $moduleName, $modules ) )
            );

            $form->addField( new BelongsToMany( 'roles' ) );
        } );

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = $this->module()->grid( $this->resource(), function( Grid $grid )
        {
            $grid->column( 'title' );
        } );

        $grid->setRenderer( new Renderer( $grid ) );

        return $grid;
    }

    /**
     * @return Form\Fields\DropdownOption[]
     */
    protected function getModuleOptions()
    {
        $modules = array_map( function( Module $module )
        {
            $class = $module->getControllerClass();
            $name = new ReflectionClass( $class );

            return new Form\Fields\DropdownOption( $class, $name->getShortName() );
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