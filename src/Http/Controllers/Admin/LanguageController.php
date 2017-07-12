<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Support\Translate\Language;
use Illuminate\Database\Eloquent\Model;

class LanguageController
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Language::class;

    /**
     * @param Model $model
     * @return Form
     */
    public function form( Model $model )
    {
        return $this->module()->form( $model, function( Form $form )
        {
            $form->addField( new Text( 'locale' ) )->rules( 'required' );
            $form->addField( new Text( 'name' ) )->rules( 'required' );
        } );
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->module()->grid( $this->resource(), function( Grid $grid )
        {
            $grid->column( 'locale' );
            $grid->column( 'name' );
        } );
    }
}