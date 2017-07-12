<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Pages\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class RedirectsController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Redirect::class;

    /**
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        $form = $this->module()->form( $model, function( Form $form )
        {
            $form->addField( new Text( 'from_url' ) )->rules( 'required' );
            $form->addField( new Text( 'to_url' ) )->rules( 'required' );
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
            $grid->column( 'from_url' );
            $grid->column( 'to_url' );
        } );

        return $grid;
    }
}