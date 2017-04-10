<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use Illuminate\Console\DetectsApplicationNamespace;

class Model extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $fillable = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        $properties = (clone $this->schema->getFields())->transform( function( $field ) {
            /**
             * @var Field $field
             */
            return 'protected $' .  $this->formatter->property( $field->getName() ) . ';';
        } );

        $replace = [
            '{{namespace}}' => $this->getNamespace(),
            '{{className}}' =>$this->getClassName(),
            '{{$tableName}}' => snake_case( $this->schema->getName() ),
            '{{fillable}}' => $this->formatter->prependSpacing( $fillable, 2 )->implode( PHP_EOL ),
            '{{properties}}' => $this->formatter->prependSpacing( $properties, 1 )->implode( PHP_EOL ),
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stubRegistry->findByName( 'model' )->getContents()
        );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->formatter->className( $this->schema->getName() );
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