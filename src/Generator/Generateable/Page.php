<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;

class Page extends StubGenerator implements Stubable
{
    use GeneratorFormatter, DetectsApplicationNamespace;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param StubRegistry $stubRegistry
     * @param Filesystem $filesystem
     * @param Model $model
     */
    public function __construct(
        StubRegistry $stubRegistry,
        Filesystem $filesystem,
        Model $model
    )
    {
        $this->stub = $stubRegistry->findByName( 'page' );
        $this->filesystem = $filesystem;
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $fieldSet = ( clone $this->model->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            // todo: sprintf
            return '$fieldSet->add( new ' . $field->getClassName() . '( \'' . $field->getName() . '\' ) );';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{use}}' => $this->useFields( clone $this->model->getFields() )->implode( PHP_EOL ),
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
        return $this->model->getClassName() . 'Page';
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