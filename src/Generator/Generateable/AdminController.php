<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Generateable\Extras\Field;
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
        $this->stub = $stubRegistry->findByName( 'view' );
        $this->filesystem = $filesystem;
        $this->model = $model;
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

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->getAppNamespace() . '\Http\Controllers\Admin\\';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return app_path( 'Http/Controllers/Admin/' . $this->getFilename() );
    }
}