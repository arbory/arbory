<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;

class Page extends StubGenerator implements Stubable
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
        $this->stub = $stubRegistry->findByName( 'page' );
        $this->filesystem = $filesystem;
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $fieldSet = ( clone $this->schema->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            // todo: sprintf
            return '$fieldSet->add( new ' . $field->getClassName() . '( \'' . $field->getName() . '\' ) );';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{use}}' => $this->useFields( clone $this->schema->getFields() )->implode( PHP_EOL ),
            '{{className}}' => $this->getClassName(),
            '{{fieldSet}}' => $this->prependSpacing( $fieldSet, 2 )->implode( PHP_EOL ),
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
        return $this->className( $this->schema->getName() ) . 'Page';
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
        return $this->getAppNamespace() . 'Pages';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Pages/' . $this->getFilename() );
    }
}