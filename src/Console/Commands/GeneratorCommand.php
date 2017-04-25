<?php

namespace CubeSystems\Leaf\Console\Commands;

use CubeSystems\Leaf\Admin\Form\Fields\HasMany;
use CubeSystems\Leaf\Admin\Form\Fields\HasOne;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Generator\Extras\Relation;
use CubeSystems\Leaf\Generator\Generatable\AdminController;
use CubeSystems\Leaf\Generator\Generatable\Controller;
use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Structure;
use CubeSystems\Leaf\Generator\Generatable\Migration;
use CubeSystems\Leaf\Generator\Generatable\Model;
use CubeSystems\Leaf\Generator\Generatable\View;
use CubeSystems\Leaf\Generator\Generatable\Page;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\MiscGenerators;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * @var Container
     */
    protected $container;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @param Container $container
     * @param GeneratorFormatter $generatorFormatter
     * @param Filesystem $fileSystem
     */
    public function __construct(
        Container $container,
        GeneratorFormatter $generatorFormatter,
        Filesystem $fileSystem
    )
    {
        $this->container = $container;
        $this->formatter = $generatorFormatter;
        $this->fileSystem = $fileSystem;

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
        $schema = $this->container->make( Schema::class );
        
        $this->line( 'Generator' );

        $schema->setName( $this->ask( 'Enter the name of the model', 'test' ) );

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

        if( $this->confirm( 'Define relations?', true ) )
        {
            $this->setupRelations( $schema );
        }

        if( $this->confirm( 'Define fields?', true ) )
        {
            $this->setupFields( $schema );
        }

        $this->line( 'Generating a model named ' . $schema->getName() );

        $tableParts = $this->formatter->getSchemaTable( $schema );

        if( $tableParts )
        {
            $this->line( 'With the following schema' );
            $this->line( '' );

            list( $header, $body ) = $tableParts;
            $this->table( $header, $body );

            $this->line( '' );
        }

        foreach( $this->selectGeneratables() as $generatableType )
        {
            /** @var StubGenerator $generatable */
            $generatable = new $generatableType(
                $this->container->make( StubRegistry::class ),
                $this->container->make( Filesystem::class ),
                $this->container->make( GeneratorFormatter::class ),
                $schema
            );

            $this->info( 'Generating ' . $generatable->getPath() . '...' );

            $generatable->generate();
        }
    }

    /**
     * @param Schema $schema
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function setupRelations( Schema $schema )
    {
        do
        {
            $relation = new Relation();

            $relation->setModel( $this->choice( 'Select model', $this->getModels(), 0 ) );
            $relation->setFieldType( $this->choice( 'Select relation', $this->getRelationFieldTypes(), 0 ) );

            $schema->addRelation( $relation );
        } while( $this->confirm( '... add another?', false ) );
    }

    /**
     * @param Schema $schema
     */
    protected function setupFields( Schema $schema )
    {
        /** @var FieldTypeRegistry $fieldTypeRegistry */
        $fieldTypeRegistry = $this->container->make( FieldTypeRegistry::class );
        $nthField = 0;

        do
        {
            $nthField++;

            $this->line( 'Define field #' . $nthField );

            $structure = new Structure();
            $field = new Field( $structure );

            $field->setName( $this->ask( 'Enter the name', 'testing' ) );

            $choices = $fieldTypeRegistry->getFieldsByType()->toArray();

            $dataType = $this->choice( 'Select the data type', array_keys( $choices ), 0 );

            $structure->setType( $dataType );

            $field->setType( $choices[ $dataType ] );

            if( $this->confirm( 'Define the structure?', false ) )
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
        } while( $this->confirm( '... add another field?', false ) );
    }

    /**
     * @return array
     */
    protected function selectGeneratables(): array
    {
        $chosenGeneratables = [];
        $generatables = new Collection( [
            Migration::class,
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ] );

        $this->info( 'Choose what you would like to generate' );
        $this->line( 'Enter a comma separated list of items, either by key or class name' );
        $this->line( '' );

        foreach( $generatables as $index => $generatable )
        {
            $reflection = new \ReflectionClass( $generatable );

            $this->output->writeln( sprintf(
                '  [<fg=yellow>%d</>] %s',
                $index, $reflection->getShortName()
            ) );
        }

        $choices = $this->ask( 'Items', '*' );

        if ( $choices === '*' )
        {
            return $generatables->toArray();
        }

        $choices = explode( ',', str_replace( ' ', '', $choices ) );

        foreach( $choices as $choice )
        {
            $chosenGeneratable = $generatables->get( $choice );

            if( !$chosenGeneratable )
            {
                $chosenGeneratable = $generatables->first( function( string $generatable ) use ( $choice )
                {
                    return Str::contains(
                        Str::snake( $generatable ),
                        Str::snake( $choice )
                    );
                } );
            }

            $chosenGeneratables[] = $chosenGeneratable;
        }

        return array_filter( $chosenGeneratables );
    }

    /**
     * @return string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getModels()
    {
        $models = [];
        $files = $this->fileSystem->files( app_path() );

        foreach( $files as $file )
        {
            if( Str::contains( $this->fileSystem->get( $file ), \Illuminate\Database\Eloquent\Model::class ) )
            {
                $class = str_replace( [ base_path() . '/', '.php', '/' ], [ '', '', '\\' ], $file );

                $models[] = ucfirst( $class );
            }
        }

        return $models;
    }

    /**
     * @return string[]
     */
    protected function getRelationFieldTypes()
    {
        return [
            HasOne::class,
            HasMany::class,
        ];
    }
}
