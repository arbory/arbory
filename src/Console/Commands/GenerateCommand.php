<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Generator\Generatable\AdminController;
use Arbory\Base\Generator\Generatable\Controller;
use Arbory\Base\Generator\Extras\Field;
use Arbory\Base\Generator\Extras\Structure;
use Arbory\Base\Generator\Generatable\Model;
use Arbory\Base\Generator\Generatable\Page;
use Arbory\Base\Generator\Generatable\View;
use Arbory\Base\Generator\GeneratorFormatter;
use Arbory\Base\Generator\Schema;
use Arbory\Base\Generator\StubGenerator;
use Arbory\Base\Services\FieldTypeRegistry;
use Arbory\Base\Services\StubRegistry;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class GenerateCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'arbory:generate {type?} {--T|table=}';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @param Application $app
     * @param DatabaseManager $databaseManager
     * @param GeneratorFormatter $generatorFormatter
     */
    public function __construct(
        Application $app,
        DatabaseManager $databaseManager,
        GeneratorFormatter $generatorFormatter
    )
    {
        $this->app = $app;
        $this->databaseManager = $databaseManager;
        $this->formatter = $generatorFormatter;

        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle()
    {
        $fromTableName = $this->option( 'table' );

        if( $fromTableName )
        {
            $this->generateFromTable( $fromTableName );
        }
    }

    /**
     * @param string $tableName
     * @return void
     */
    public function generateFromTable( string $tableName )
    {
        /** @var FieldTypeRegistry $fieldTypeRegistry */
        $fieldTypeRegistry = $this->app->make( FieldTypeRegistry::class );
        $connection = $this->databaseManager->connection();

        if( !$connection->getDoctrineSchemaManager()->tablesExist( [ $tableName ] ) )
        {
            $this->error( 'No table by that name found!' );
            return;
        }

        $columns = $connection->getDoctrineSchemaManager()->listTableColumns( $tableName );

        /** @var Schema $schema */
        $schema = $this->app->make( Schema::class );
        $schema->setNamePlural( $tableName );

        // TODO: add argument to define the singular name or guess from plural

        foreach( $columns as $column )
        {
            /** @var Column $column */
            $structure = new Structure();
            $field = new Field( $structure );

            $field->setName( $column->getName() );
            $field->setType( $fieldTypeRegistry->resolve( $column->getName(), $column->getType()->getName() ) );
            $structure->setAutoIncrement( $column->getAutoincrement() );
            $structure->setNullable( !$column->getNotnull() );
            $structure->setDefaultValue( $column->getDefault() );
            $structure->setLength( (int) $column->getLength() );

            $schema->addField( $field );
        }

        $generatables = [
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ];

        foreach($generatables as $generatableType)
        {
            $typeArgument = $this->argument( 'type' );

            if(
                $typeArgument &&
                !Str::contains( Str::lower( $generatableType ), Str::lower( $typeArgument ) )
            )
            {
                continue;
            }

            /** @var StubGenerator $generatable */
            $generatable = new $generatableType(
                $this->app->make( StubRegistry::class ),
                $this->app->make( Filesystem::class ),
                $this->app->make( GeneratorFormatter::class ),
                $schema
            );

            $this->info( 'Generating ' . $generatable->getPath() . '...' );

            $generatable->generate();
        }
    }
}
