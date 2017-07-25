<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\HasOne;
use Arbory\Base\Generator\Extras\Relation;
use Arbory\Base\Generator\Generatable\AdminController;
use Arbory\Base\Generator\Generatable\Controller;
use Arbory\Base\Generator\Extras\Field;
use Arbory\Base\Generator\Extras\Structure;
use Arbory\Base\Generator\Generatable\Migration;
use Arbory\Base\Generator\Generatable\Model;
use Arbory\Base\Generator\Generatable\TranslationModel;
use Arbory\Base\Generator\Generatable\View;
use Arbory\Base\Generator\Generatable\Page;
use Arbory\Base\Generator\GeneratorFormatter;
use Arbory\Base\Generator\Schema;
use Arbory\Base\Generator\StubGenerator;
use Arbory\Base\Services\FieldTypeRegistry;
use Arbory\Base\Services\StubRegistry;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class GeneratorCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'arbory:generator';

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
     * @var FieldTypeRegistry
     */
    protected $fieldTypeRegistry;

    /**
     * @param Container $container
     * @param GeneratorFormatter $generatorFormatter
     * @param Filesystem $fileSystem
     * @param FieldTypeRegistry $fieldTypeRegistry
     */
    public function __construct(
        Container $container,
        GeneratorFormatter $generatorFormatter,
        Filesystem $fileSystem,
        FieldTypeRegistry $fieldTypeRegistry
    )
    {
        $this->container = $container;
        $this->formatter = $generatorFormatter;
        $this->fileSystem = $fileSystem;
        $this->fieldTypeRegistry = $fieldTypeRegistry;

        parent::__construct();
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function handle()
    {
        $schema = $this->setupSchema();

        $this->setupFields( $schema );
        $this->setupRelations( $schema );
        $this->generate( $schema );
    }

    /**
     * @return Schema
     * @throws \InvalidArgumentException
     */
    protected function setupSchema(): Schema
    {
        $schema = $this->container->make( Schema::class );

        $nameParts = explode( ',', $this->askImportant( 'Model name', null, 'singular, plural' ) );

        if( empty( $nameParts[ 0 ] ) )
        {
            throw new \InvalidArgumentException( 'Name cannot be empty' );
        }
        
        if( !isset( $nameParts[ 1 ] ) )
        {
            $nameParts[ 1 ] = $nameParts[ 0 ] . 's';
        }

        $schema->setNameSingular( trim( $nameParts[ 0 ] ) );
        $schema->setNamePlural( trim( $nameParts[ 1 ] ) );

        $schema->useId( $this->confirm( 'Add an id field?', true ) );
        $schema->useTimestamps( $this->confirm( 'Add created and updated fields?', true ) );

        if( $this->confirm( 'Add owner fields?', false ) )
        {
            $this->addOwnerFields( $schema );
        }

        return $schema;
    }

    /**
     * @param Schema $schema
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function setupRelations( Schema $schema )
    {
        if( $this->askEnterSection( 'Define relations?', true ) )
        {
            do
            {
                $schema->addRelation( $this->setupRelation() );
            } while( $this->confirm( '... add another?', true ) );
        }
    }

    /**
     * @return Relation
     */
    protected function setupRelation(): Relation
    {
        $relation = new Relation();
        $models = $this->getModels();

        $relation->setFieldType( $this->choice( 'Select relation', $this->getRelationFieldTypes(), 0 ) );

        $key = $this->choice( 'Select model', $models, 0 );
        $relation->setModel( $models[ $key ] );

        return $relation;
    }

    /**
     * @param Schema $schema
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function setupFields( Schema $schema )
    {
        if( $this->askEnterSection( 'Define fields?', true ) )
        {
            do
            {
                $schema->addField( $this->setupField() );
            } while( $this->confirm( '... add another field?', true ) );
        }
    }

    /**
     * @return Field
     */
    protected function setupField(): Field
    {
        $structure = new Structure();
        $field = new Field( $structure );

        $field->setName( $this->ask( 'Enter the name' ) );

        $choices = $this->fieldTypeRegistry->getFieldsByType()->toArray();

        $dataType = $this->choice( 'Select the data type', array_keys( $choices ), 0 );

        $structure->setType( $dataType );

        $field->setType( $choices[ $dataType ] );

        $structure->setTranslatable( $this->confirm( 'Is it translatable?', false ) );

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

        return $field;
    }

    /**
     * @param Schema $schema
     * @return void
     */
    protected function generate( Schema $schema )
    {
        $this->line( 'We will be generating a schema named ' . $schema->getNameSingular() );

        $tableParts = $this->formatter->getSchemaTable( $schema );

        if( $tableParts )
        {
            $this->line( 'With the following schema' );
            $this->line( '' );

            list( $header, $body ) = $tableParts;
            $this->table( $header, $body );

            $this->line( '' );
        }

        $selectedGeneratables = $this->selectGeneratables();

        if( in_array( Model::class, $selectedGeneratables ) && $schema->hasTranslatables() )
        {
            $selectedGeneratables[] = TranslationModel::class;
        }

        foreach( $selectedGeneratables as $generatableType )
        {
            /** @var StubGenerator $generatable */
            $generatable = new $generatableType(
                $this->container->make( StubRegistry::class ),
                $this->container->make( Filesystem::class ),
                $this->container->make( GeneratorFormatter::class ),
                $schema
            );

            $this->info( 'Generating ' . $generatable->getPath() . '...' );

            $generatable->setSelectGeneratables( new Collection( $selectedGeneratables ) );
            $generatable->generate();
        }
    }

    /**
     * @return array
     */
    protected function selectGeneratables(): array
    {
        $items = $this->getGeneratables();
        $groups = $this->getGeneratableGroups();

        /** @var array $choices */
        $choices = $this->choice( 'Choose generatables (comma seperated)', $this->getGeneratableChoices(), 0, null, true );

        foreach( $choices as $index => $choice )
        {
            if( isset( $groups[ $choice ] ) )
            {
                $choices = array_merge( $choices, $groups[ $choice ] );
            }

            if( isset( $items[ $choice ] ) )
            {
                $choices[] = $items[ $choice ];
            }

            unset( $choices[ array_search( $choice, $choices ) ] );
        }

        return array_filter( $choices );
    }

    /**
     * @return string[]
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getModels()
    {
        $models = [];
        $files = array_merge(
            $this->fileSystem->files( app_path() ),
            $this->fileSystem->files( app_path( 'pages' ) )
        );

        foreach( $files as $file )
        {
            if( Str::contains( $this->fileSystem->get( $file ), \Illuminate\Database\Eloquent\Model::class ) )
            {
                $class = str_replace( [ base_path() . '/', '.php', '/' ], [ '', '', '\\' ], $file );

                $models[] = ucfirst( $class );
            }
        }

        return array_merge( $models, $this->fieldTypeRegistry->getFieldsByRelation()->toArray() );
    }

    /**
     * @param string $question
     * @param mixed|null $default
     * @param mixed|null $hint
     * @return mixed
     */
    protected function askImportant( string $question, $default = null, $hint = null )
    {
        $helper = $this->getHelper( 'question' );
        $compiled = new Question(
            $this->formatter->line( $question, $default, $hint ),
            $default
        );

        $result = $helper->ask( $this->input, $this->output, $compiled );
        $this->line( '' );

        return $result;
    }

    /**
     * @param string $message
     * @param bool $default
     * @return bool
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function askEnterSection( string $message, bool $default )
    {
        $helper = $this->getHelper( 'question' );
        $question = new ConfirmationQuestion(
            sprintf( ' <fg=blue>%s</> [<fg=yellow>%s</>]: ' . PHP_EOL . ' > ', $message, $default ? 'yes' : 'no' ),
            $default
        );

        $result = $helper->ask( $this->input, $this->output, $question );
        $this->line( '' );

        return $result;
    }

    /**
     * @return array
     */
    protected function getGeneratableChoices(): array
    {
        $groups = $this->getGeneratableGroups();
        $generatables = $this->getGeneratables();

        foreach( $groups as &$group )
        {
            foreach( $group as &$item )
            {
                $item = class_basename( $item );
            }

            $group = implode( ', ', $group );
        }

        foreach( $generatables as &$generatable )
        {
            $generatable = class_basename( $generatable );
        }

        return array_merge( $groups, $generatables );
    }

    /**
     * @param Schema $schema
     * @return void
     */
    protected function addOwnerFields( Schema $schema )
    {
        $structure = new Structure();
        $field = new Field( $structure );
        $field->setName( 'owner_type' );
        $field->setType( 'string' );

        $schema->addField( $field );

        $structure = new Structure();
        $structure->setNullable( true );
        $field = new Field( $structure );
        $field->setName( 'owner_id' );
        $field->setType( 'integer' );

        $schema->addField( $field );
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

    /**
     * @return string[]
     */
    protected function getGeneratables(): array
    {
        return [
            Migration::class,
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ];
    }

    /**
     * @return array
     */
    protected function getGeneratableGroups(): array
    {
        return [
            'P' => [
                Migration::class,
                Controller::class,
                Page::class,
                View::class
            ],
            'M' => [
                Migration::class,
                AdminController::class,
                Model::class
            ],
        ];
    }
}
