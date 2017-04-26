<?php

namespace CubeSystems\Leaf\Generator\Generatable;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Structure;
use CubeSystems\Leaf\Generator\Stubable;
use CubeSystems\Leaf\Generator\StubGenerator;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Migration extends StubGenerator implements Stubable
{
    /**
     * @return string
     */
    public function getCompiledControllerStub(): string
    {
        return $this->stubRegistry->make( 'migration', [
            'className' => $this->getClassName(),
            'modelTableName' => Str::snake( $this->schema->getNamePlural() ),
            'pageTableName' => Str::snake( $this->schema->getNameSingular() ),
            'schemaFields' => $this->getCompiledSchemaFields()
        ] );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return 'Create' . $this->formatter->className( $this->schema->getNamePlural() ) . 'Table';
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
            snake_case( $this->schema->getNamePlural() )
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
    protected function buildSecondArgument( Structure $structure )
    {
        $argument = null;

        if( $structure->getType() === 'integer' )
        {
            $argument = $structure->isAutoIncrement();
        }
        elseif( $structure->getType() === 'string' )
        {
            $argument = $structure->getLength();
        }

        return $argument ? ', ' . $argument : '';
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

    /**
     * @return string
     */
    protected function getCompiledSchemaFields(): string
    {
        $fields = new Collection();

        if( $this->schema->usesId() )
        {
            $fields->push( '$table->increments( \'id\' );' );
        }

        if( $this->schema->usesTimestamps() )
        {
            $fields->push( '$table->timestamps();' );
        }

        $fields->merge( $this->schema->getFields()->map( function( Field $field )
        {
            $structure = $field->getStructure();

            return sprintf(
                '$table->%s( \'%s\'%s )%s;',
                $structure->getType(),
                $field->getDatabaseName(),
                $this->buildSecondArgument( $structure ),
                $this->buildColumn( $structure )
            );
        } ) );

        return $this->formatter->indent( $fields->implode( PHP_EOL ), 3 );
    }
}