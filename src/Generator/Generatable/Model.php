<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Facades\App;

class Model extends StubGenerator implements Stubable
{
    use DetectsApplicationNamespace;

    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        /** @var FieldTypeRegistry $fieldTypeRegistry */
        $fieldTypeRegistry = App::make( FieldTypeRegistry::class );

        $fillable = ( clone $this->schema->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );

        $properties = ( clone $this->schema->getFields() )->transform( function( $field ) use ( $fieldTypeRegistry )
        {
            /**
             * @var Field $field
             */
            $replace = [
                '{{docVar}}' => $fieldTypeRegistry->getFieldTypeHint( $field->getType() ),
                '{{propertyScope}}' => 'protected',
                '{{propertyName}}' => $this->formatter->property( $field->getName() ),
            ];

            $stub = str_replace(
                array_keys( $replace ),
                array_values( $replace ),
                $this->stubRegistry->findByName( 'property' )->getContents()
            );

            return $stub . PHP_EOL;
        } );

        $propertiesFirst = $properties->first();
        $properties->put( 0, preg_replace( '/    /', '', $propertiesFirst, 1 ) );

        return $this->stubRegistry->make( 'model', [
            'namespace' => $this->getNamespace(),
            'className' => $this->getClassName(),
            '$tableName' => snake_case( $this->schema->getName() ),
            'fillable' => $this->formatter->prependSpacing( $fillable, 2 )->implode( PHP_EOL ),
            'properties' => $properties->implode( PHP_EOL ),
        ] );
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