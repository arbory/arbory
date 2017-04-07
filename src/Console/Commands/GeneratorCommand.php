<?php

namespace CubeSystems\Leaf\Console\Commands;

use CubeSystems\Leaf\Admin\Form\Fields\Checkbox;
use CubeSystems\Leaf\Admin\Form\Fields\DateTime;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\Richtext;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Form\Fields\Textarea;
use CubeSystems\Leaf\Generator\Generateable\AdminController;
use CubeSystems\Leaf\Generator\Generateable\Controller;
use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\Generateable\Extras\Structure;
use CubeSystems\Leaf\Generator\Generateable\Migration;
use CubeSystems\Leaf\Generator\Generateable\Model;
use CubeSystems\Leaf\Generator\Generateable\View;
use CubeSystems\Leaf\Generator\Generateable\Page;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

class GeneratorCommand extends Command
{
    use ConfirmableTrait, GeneratorFormatter;

    /**
     * @var string
     */
    protected $name = 'leaf:generator';

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
    public function handle()
    {
        /**
         * @var Schema $schema
         */
        $schema = $this->app->make( Schema::class );

        $schema->setName( $this->ask( 'Please enter the name of the model' ) );

        if( $this->confirm( 'Would you like to define the fields?', true ) )
        {
            if( $this->confirm( 'Would you like an auto increment index field?', true ) )
            {
                $structure = new Structure();
                $field = new Field( $structure );

                $field->setName( 'id' );
                $field->setType( Hidden::class );
                $structure->setType( 'increments' );
                $structure->setAutoIncrement( true );

                $schema->addField( $field );
            }

            $schema->setTimestamps( $this->confirm( 'Would you like to add the default laravel timestamp fields?', true ) );

            $this->setupFields( $schema );
        }

        $this->line( 'We are going to generate a model named ' . $schema->getName() );
        $this->line( 'With the following fields' );

        list( $header, $body ) = $this->getSchemaTable( $schema );

        $this->table( $header, $body );

        $generateables = [
            Migration::class,
            Model::class,
            Page::class,
            Controller::class,
            View::class,
            AdminController::class
        ];

        foreach($generateables as $generateableType)
        {
            /** @var StubGenerator $generateable */
            $generateable = new $generateableType(
                $this->app->make( StubRegistry::class ),
                $this->app->make( Filesystem::class ),
                $schema
            );

            $this->line( 'Generating ' . $generateable->getPath() . '...' );

            $generateable->generate();
        }
        // LeafRoute
    }

    /**
     * @param Schema $schema
     */
    protected function setupFields( $schema )
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

            $schema->addField( $field );
        } while( $this->confirm( 'Add another field?' ) );
    }
}
