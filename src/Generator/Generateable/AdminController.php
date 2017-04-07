<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;

class AdminController extends StubGenerator implements Stubable
{
    use GeneratorFormatter, DetectsApplicationNamespace;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     * @param Schema $schema
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem,
        Schema $schema
    )
    {
        $this->stub = $stubRegistry->findByName( 'admin_controller' );
        $this->filesystem = $filesystem;
        $this->schema = $schema;
    }

    public function generate()
    {
        parent::generate();

        $this->registerAdminRoute();
    }

    /**
     * @return void
     */
    protected function registerAdminRoute()
    {
        $path = base_path( 'routes/admin.php' );
        $route = 'AdminRoute::register( ' . $this->getNamespace() . $this->getClassName() . '::class );';

        if( !str_contains( $this->filesystem->get( $path ), $route ) )
        {
            $this->filesystem->append(
                $path,
                PHP_EOL . $route
            );
        }
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $useFields = $this->useFields( clone $this->schema->getFields() );
        $useFields->push(
            $this->use( $this->getAppNamespace() . $this->className( $this->schema->getName() ) )
        );

        $formFields = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '$form->addField( new ' .  $field->getClassName() . '(\'' . $field->getName() . '\') );';
        } );

        $gridFields = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '$grid->column(\'' .  $field->getName() . '\');';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{className}}' => $this->getClassName(),
            '{{resourceName}}' => $this->className( $this->schema->getName() ). '::class',
            '{{use}}' => $useFields->implode( PHP_EOL ),
            '{{formFields}}' => $this->prependSpacing( $formFields, 3 )->implode( PHP_EOL ),
            '{{gridFields}}' => $this->prependSpacing( $gridFields, 3 )->implode( PHP_EOL ),
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stub->getContents()
        );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className( $this->schema->getName() ) . 'Controller';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() .'.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->getAppNamespace() . 'Http\Controllers\Admin\\';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Http/Controllers/Admin/' . $this->getFilename() );
    }
}