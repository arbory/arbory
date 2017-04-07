<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\DetectsApplicationNamespace;

class Model extends StubGenerator implements Stubable
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
        $this->stub = $stubRegistry->findByName( 'model' );
        $this->filesystem = $filesystem;
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        // TODO: to camel case
        $fillable = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '\'' . $field->getName() . '\',';
        } );

        $properties = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return 'protected $' .  $field->getName() . ';';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{className}}' =>$this->getClassName(),
            '{{$tableName}}' => snake_case( $this->schema->getName() ),
            '{{fillable}}' => $this->prependSpacing( $fillable, 2 )->implode( PHP_EOL ),
            '{{properties}}' => $this->prependSpacing( $properties, 1 )->implode( PHP_EOL ),
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
        return $this->className( $this->schema->getName() );
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() . '.php';
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return rtrim( $this->getAppNamespace(), '\\' );
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( $this->getFilename() );
    }
}