<?php

namespace CubeSystems\Leaf\Generator\Generateable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Structure;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use DateTimeImmutable;

class Migration extends StubGenerator implements Stubable
{
    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        $schemaFields = ( clone $this->schema->getFields() )->transform( function( $field )
        {
            /**
             * @var Field $field
             */
            $structure = $field->getStructure();

            return sprintf(
                '$table->%s( \'%s\' )%s;',
                $structure->getType(),
                $field->getDatabaseName(),
                $this->buildColumn( $structure )
            );
        } );

        $replace = [
            '{{className}}' => $this->getClassName(),
            '{{schemaName}}' => snake_case( $this->schema->getName() ),
            '{{schemaFields}}' => $this->formatter->prependSpacing( $schemaFields,3 )->implode( PHP_EOL ),
            '{{downAction}}' => 'Schema::dropIfExists( \'' . $this->schema->getName() . '\' );'
        ];

        return str_replace(
            array_keys( $replace ),
            array_values( $replace ),
            $this->stubRegistry->findByName( 'migration' )->getContents()
        );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return 'Create' . $this->formatter->className(  $this->schema->getName() ) . 'Table';
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
            strtolower( $this->schema->getName() )
        );
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return (string) null;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return base_path( 'database/migrations/' . $this->getFilename() );
    }

    /**
     * @param Structure $structure
     * @return string
     */
    protected function buildColumn( Structure $structure )
    {
        $builder = '';
        $defaultValue = $structure->getDefaultValue();

        if( $structure->isNullable() )
        {
            $builder .= '->nullable()';
        }

        if( $defaultValue )
        {
            $builder .= '->default( \'' . $defaultValue . '\' )';
        }

        return $builder;
    }
}