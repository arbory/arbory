<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;

class AdminController implements Generateable
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
        $this->stub = $stubRegistry->findByName( 'admin_controller' );
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function generate(): bool
    {
        return (bool) file_put_contents(
            app_path( 'Http/Controllers/Admin/' . $this->getFilename() ),
            $this->getCompiledControllerStub()
        );
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $useFields = $this->useFields( clone $this->model->getFields() );

        $useFields->push( $this->use( $this->model->use() ) );

        $formFields = (clone $this->model->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '$form->addField( new ' .  $field->getClassName() . '(\'' . $field->getName() . '\') );';
        } );

        $gridFields = (clone $this->model->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '$grid->column(\'' .  $field->getName() . '\');';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{className}}' => $this->getClassName(),
            '{{resourceName}}' => $this->model->getClassName() . '::class',
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
        return $this->model->getClassName() . 'Controller';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->getClassName() .'.php';
    }

    public function getNamespace(): string
    {
        return 'App\Http\Controllers\Admin\\';
    }
}