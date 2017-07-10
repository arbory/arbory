<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Settings\Setting;
use CubeSystems\Leaf\Admin\Settings\SettingDefinition;
use CubeSystems\Leaf\Admin\Tools\ToolboxMenu;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Providers\SettingsServiceProvider;
use CubeSystems\Leaf\Services\SettingFactory;
use CubeSystems\Leaf\Services\SettingRegistry;
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
     * @var SettingRegistry
     */
    protected $settingRegistry;

    /**
     * @param SettingRegistry $settingRegistry
     */
    public function __construct(
        SettingRegistry $settingRegistry
    )
    {
        $this->settingRegistry = $settingRegistry;

        /** @var SettingsServiceProvider $settingsService */
        $settingsService = \App::make( SettingsServiceProvider::class );
        $settingsService->importFromDatabase();
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        $definition = $this->settingRegistry->find( $model->getKey() );

        $form = $this->module()->form( $model, function( Form $form ) use ( $definition )
        {
            $type = $definition->getType();

            $form->addField( new $type( 'value' ) )->setValue( $definition->getValue() );
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
            $grid->column( 'value' );
        } );

        return $grid
            ->tools( [] )
            ->items( $this->getSettings() )
            ->paginate( false );
    }

    /**
     * @return array
     */
    protected function getSettings()
    {
        /** @var SettingFactory $factory */
        $factory = \App::make( SettingFactory::class );
        $result = null;

        foreach( $this->settingRegistry->getSettings() as $key => $_ )
        {
            $result[ $key ] = $factory->build( $key );
        }

        return $result;
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