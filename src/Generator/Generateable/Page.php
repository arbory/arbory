<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;

class Page implements Generateable
{
    use GeneratorFormatter;

    /**
     * @var Stub
     */
    protected $stub;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param StubRegistry $stubRegistry
     * @param Model $model
     */
    public function __construct( StubRegistry $stubRegistry, Model $model )
    {
        $this->stub = $stubRegistry->findByName( 'page' );
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function generate(): bool
    {
        return (bool) file_put_contents(
            app_path( 'Pages/' . $this->getFilename() ),
            $this->getCompiledControllerStub()
        );
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
        return 'App\Pages';
    }
}