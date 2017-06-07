<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\BelongsToMany;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Menu\Admin\Grid\Renderer;
use CubeSystems\Leaf\Nodes\MenuItem;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

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
     * @param ModuleRegistry $moduleRegistry
     */
    public function __construct( ModuleRegistry $moduleRegistry )
    {
        $this->moduleRegistry = $moduleRegistry;
    }

    /**
     * @param MenuItem $model
     * @return Form
     */
    protected function form( MenuItem $model )
    {
        $form = $this->module()->form( $model, function ( Form $form )
        {
            $menuItems = $this->getMenuItemOptions();

            $form->addField( new Text( 'title' ) );

            $form
                ->addField( new Form\Fields\Select( 'after_id' ) )
                ->options( $menuItems );

            $form
                ->addField( new Form\Fields\Select( 'parent_id' ) )
                ->options( $menuItems );

            $form
                ->addField( new Form\Fields\Select( 'module' ) )
                ->options( $this->getModuleOptions() );

            $form->addField( new BelongsToMany( 'roles' ) );
        } );

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = $this->module()->grid( $this->resource(), function ( Grid $grid )
        {
            $grid->column( 'title' );
        } );

        $grid->filter( function( Grid\Filter $filter )
        {
            // todo: add a way to disable pagination
            $filter->setPerPage( 1000 );
        } );

        $grid->setRenderer( new Renderer( $grid ) );

        return $grid;
    }

    /**
     * @return Collection
     */
    protected function getModuleOptions()
    {
        return collect( $this->moduleRegistry->getModulesByControllerClass() )
            ->prepend('Group','')
            ->map( function ( $value )
            {
                return (string) $value;
            } );
    }

    /**
     * @return Collection
     */
    protected function getMenuItemOptions()
    {
        return MenuItem::all()
            ->keyBy( 'id' )
            ->map( function ( MenuItem $item )
            {
                return $item->getTitle();
            } )
            ->prepend('','');
    }
}
