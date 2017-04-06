<?php

namespace CubeSystems\Leaf\Console\Commands;

use CubeSystems\Leaf\Admin\Form\Fields\Checkbox;
use CubeSystems\Leaf\Admin\Form\Fields\DateTime;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\Richtext;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Form\Fields\Textarea;
use CubeSystems\Leaf\Generator\Generateable\AdminController;
use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\Generateable\Extras\Structure;
use CubeSystems\Leaf\Generator\Generateable\Migration;
use CubeSystems\Leaf\Generator\ModelGenerator;
use CubeSystems\Leaf\Generator\Generateable\Model;
use CubeSystems\Leaf\Generator\Generateable\Page;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Foundation\Application;

class GeneratorCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $name = 'leaf:generator';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct( Application $app )
    {
        $this->app = $app;

        parent::__construct();
    }

    /**
     * @return void
     */
    public function fire()
    {
        /**
         * @var Model $model
         */
        $model = $this->app->make( Model::class );

        $model->setName( $this->ask( 'Please enter the name of the model' ) );

        if( $this->confirm( 'Would you like to define the fields?', true ) )
        {
            if( $this->confirm( 'Would you like an auto increment index field?', true ) )
            {
                $structure = new Structure();
                $field = new Field( $structure );

                $field->setName( 'id' );
                $field->setType( Hidden::class );
                $structure->setAutoIncrement( true );

                $model->addField( $field );
            }

            $model->setTimestamps( $this->confirm( 'Would you like to add the default laravel timestamp fields?', true ) );

            $this->setupFields( $model );
        }

        $this->line( 'We are going to generate a model named ' . $model->getName() );
        $this->line( 'With the following fields' );

        $header = array_merge(
            [ 'name' ],
            array_keys( $model->getFields()->first()->getStructure()->values() )
        );

        $this->table(
            $header,
            (clone $model->getFields())->transform(function($item) {
                /** @var Field $item */
                return array_merge( [ $item->getName() ], $item->getStructure()->values() );
            })
        );

        /**
         * @var Page $page
         */
        $page = new Page(
            $this->app->make( StubRegistry::class ),
            $model
        );

        $migration = new Migration(
            $this->app->make( StubRegistry::class ),
            $model
        );

        $adminController = new AdminController(
            $this->app->make( StubRegistry::class ),
            $model
        );

        $generator = new ModelGenerator( $model );

        $model->generate();
        $page->generate();
        $migration->generate();
        $adminController->generate();

        // LeafRoute
    }

    /**
     * @param Model $model
     */
    protected function setupFields( $model )
    {
        do
        {
            $structure = new Structure();
            $field = new Field( $structure );

            $field->setName( $this->ask( 'Please enter the name of the field' ) );

            $choices = [
                'string' => Text::class,
                'text' => Textarea::class,
                'boolean' => Checkbox::class,
                'datetime' => DateTime::class,
                'longtext' => Richtext::class,
            ];

            $dataType = $this->choice( 'Select the data type', array_keys( $choices), 0 );

            $structure->setType( $dataType );

            $field->setType( $choices[ $dataType ] );

            if( $this->confirm( 'Would you like to define the fields database structure', true ) )
            {
                $structure->setPrimary( $this->confirm( 'Is the field primary?', false ) );
                $structure->setNullable( $this->confirm( 'Can the field be null?', false ) );
                $structure->setDefaultValue( $this->ask( 'Set the default value', 'none' ) );
                $structure->setLength( $this->ask( 'Set the maximum length', 0 ) );
            }

            $model->addField( $field );
        } while( $this->confirm( 'Add another field?' ) );
    }
}
