<?php

namespace CubeSystems\Leaf\Generators;

use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use DateTimeImmutable;

class Migration implements Generateable
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
    public function generate()
    {
        return (bool) file_put_contents(
            base_path( 'database/migrations/' . $this->getFilename() ),
            $this->getCompiledControllerStub()
        );
    }

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $schemaFields = ( clone $this->model->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            return sprintf(
                '$table->%s(\'%s\');',
                $field->getStructure()->getType(),
                $field->getDatabaseName()
            );
        } );

        $replace = [
            '{{className}}' => $this->getClassName(),
            '{{schemaName}}' => $this->model->getDatabaseName(),
            '{{schemaFields}}' => $this->prependSpacing( $schemaFields,3 )->implode( PHP_EOL ),
            '{{downAction}}' => 'Schema::dropIfExists( \'' . $this->model->getDatabaseName() . '\' );'
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stub // todo : but  y
        );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return 'Create' . $this->model->getClassName() . 'Table';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        $time = new DateTimeImmutable();

        return sprintf(
            '%s_create_%s_table.php',
            $time->format( 'Y_m_d_His' ),
            snake_case( $this->model->getName() )
        );
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return null;
    }
}