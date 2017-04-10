<?php

namespace CubeSystems\Leaf\Console\Commands;

use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Generator\Generateable\AdminController;
use CubeSystems\Leaf\Generator\Generateable\Controller;
use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Structure;
use CubeSystems\Leaf\Generator\Generateable\Migration;
use CubeSystems\Leaf\Generator\Generateable\Model;
use CubeSystems\Leaf\Generator\Generateable\View;
use CubeSystems\Leaf\Generator\Generateable\Page;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

class GeneratorCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'leaf:generator';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @param Application $app
     * @param GeneratorFormatter $generatorFormatter
     */
    public function __construct(
        Application $app,
        GeneratorFormatter $generatorFormatter
    )
    {
        $this->app = $app;
        $this->formatter = $generatorFormatter;

        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        /**
         * @var Schema $schema
         */
        $schema = $this->app->make( Schema::class );

        $schema->setName( $this->ask( 'Enter the name of the model' ) );

        if( $this->confirm( 'Define the fields?', true ) )
        {
            if( $this->confirm( 'Add an id field?', true ) )
            {
                $structure = new Structure();
                $field = new Field( $structure );

                $field->setName( 'id' );
                $field->setType( Hidden::class );
                $structure->setType( 'increments' );
                $structure->setAutoIncrement( true );

                $schema->addField( $field );
            }

            $schema->setTimestamps( $this->confirm( 'Add created and updated fields?', true ) );

            $this->setupFields( $schema );
        }

        $this->line( 'We are going to generate a model named ' . $schema->getName() );
        $this->line( 'With the following fields' );

        list( $header, $body ) = $this->formatter->getSchemaTable( $schema );

        $this->table( $header, $body );

        $generateables = [
            Migration::class,
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ];

        foreach( $generateables as $generateableType )
        {
            /** @var StubGenerator $generateable */
            $generateable = new $generateableType(
                $this->app->make( StubRegistry::class ),
                $this->app->make( Filesystem::class ),
                $this->app->make( GeneratorFormatter::class ),
                $schema
            );

            $this->info( 'Generating ' . $generateable->getPath() . '...' );

            $generateable->generate();
        }
    }

    /**
     * @param Schema $schema
     */
    protected function setupFields( $schema )
    {
        /** @var FieldTypeRegistry $fieldTypeRegistry */
        $fieldTypeRegistry = $this->app->make( FieldTypeRegistry::class );

        do
        {
            $structure = new Structure();
            $field = new Field( $structure );

            $field->setName( $this->ask( 'Enter the name' ) );

            $choices = $fieldTypeRegistry->getFieldsByType()->toArray();

            $dataType = $this->choice( 'Select the data type', array_keys( $choices ), 0 );

            $structure->setType( $dataType );

            $field->setType( $choices[ $dataType ] );

            if( $this->confirm( 'Define the structure?', true ) )
            {
                if( $structure->getType() === 'integer' )
                {
                    $structure->setLength( $this->confirm( 'Is it auto increment?', false ) );
                }

                $structure->setNullable( $this->confirm( 'Can it be null?', false ) );
                $structure->setDefaultValue( $this->ask( 'Set the default value', false ) );

                if( $structure->getType() === 'string' )
                {
                    $structure->setLength( $this->ask( 'Set the maximum length', 0 ) );
                }
            }

            $schema->addField( $field );
        } while( $this->confirm( 'Add another field?', true ) );
    }
}
