<?php

namespace CubeSystems\Leaf\Console\Commands;

use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Generator\Generateable\AdminController;
use CubeSystems\Leaf\Generator\Generateable\Controller;
use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\Generateable\Extras\Structure;
use CubeSystems\Leaf\Generator\Generateable\Model;
use CubeSystems\Leaf\Generator\Generateable\Page;
use CubeSystems\Leaf\Generator\Generateable\View;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class GenerateCommand extends Command
{
    use GeneratorFormatter;

    /**
     * @var string
     */
    protected $name = 'leaf:generate';

    /**
     * @var string
     */
    protected $signature = 'leaf:generate {type?} {--T|table=}';

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
     * @param Application $app
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        Application $app,
        DatabaseManager $databaseManager
    )
    {
        $this->app = $app;
        $this->databaseManager = $databaseManager;

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
        $connection = $this->databaseManager->connection();

        if( !$connection->getDoctrineSchemaManager()->tablesExist( [ $tableName ] ) )
        {
            $this->error( 'No table by that name found!' );
            return;
        }

        $columns = $connection->getDoctrineSchemaManager()->listTableColumns( $tableName );

        /** @var Schema $schema */
        $schema = $this->app->make( Schema::class );
        $schema->setName( $tableName );

        foreach( $columns as $column )
        {
            /** @var Column $column */
            $structure = new Structure();
            $field = new Field( $structure );

            $field->setName( $column->getName() );
            $field->setType( Hidden::class );//todo: resolve type
            $structure->setAutoIncrement( $column->getAutoincrement() );
            $structure->setNullable( !$column->getNotnull() );
            $structure->setDefaultValue( $column->getDefault() );
            $structure->setLength( (int) $column->getLength() );

            $schema->addField( $field );
        }

        list( $header, $body ) = $this->getSchemaTable( $schema );

        $this->table( $header, $body );

        $generateables = [
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ];

        foreach($generateables as $generateableType)
        {
            $typeArgument = $this->argument( 'type' );

            if( $typeArgument && !Str::contains( $generateableType, $typeArgument ) )
            {
                continue;
            }

            /** @var StubGenerator $generateable */
            $generateable = new $generateableType(
                $this->app->make( StubRegistry::class ),
                $this->app->make( Filesystem::class ),
                $schema
            );

            $this->line( 'Generating ' . $generateable->getPath() . '...' );

            $generateable->generate();
        }
    }
}