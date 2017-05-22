<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Settings\Setting;
use CubeSystems\Leaf\Admin\Tools\ToolboxMenu;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Services\SettingFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Setting::class;

    /**
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        $type = $this->getFieldType( $model );
        $value = $this->getSettingProperty( $model, 'value' );

        $form = $this->module()->form( $model, function( Form $form ) use ( $type, $value )
        {
            $form->addField( new $type( 'value' ) )->setValue( $value );
            $form->addField( new Hidden( 'type' ) )->setValue( $type );
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
            $grid->column( 'name' );
        } );

        return $grid
            ->tools( [] )
            ->items( $this->getSettings() )
            ->paginate( false );
    }

    /**
     * @param Model $model
     * @param string $property
     * @return mixed
     */
    protected function getSettingProperty( Model $model, string $property )
    {
        return config( 'settings.' . $model->getKey() . '.' . $property );
    }

    /**
     * @param Model $model
     * @return string
     */
    protected function getFieldType( Model $model )
    {
        $setting = $this->getSettingProperty( $model, 'type' );

        if( $model->type )
        {
            return $model->type;
        }

        return $setting[ 'type' ] ?? Text::class;
    }

    /**
     * @return array
     */
    protected function getSettings()
    {
        $settings = config( 'settings' );

        foreach( $settings as $name => $parameters )
        {
            $settings[ $name ] = SettingFactory::build( $name, $parameters );
        }

        return $settings;
    }

    /**
     * @param \CubeSystems\Leaf\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox( ToolboxMenu $tools )
    {
        $model = $tools->model();

        $tools->add( 'edit', $this->url( 'edit', $model->getKey() ) );
    }
}