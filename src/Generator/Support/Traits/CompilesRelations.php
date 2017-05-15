<?php

namespace CubeSystems\Leaf\Generator\Support\Traits;

use CubeSystems\Leaf\Admin\Form\Fields\HasMany;
use CubeSystems\Leaf\Admin\Form\Fields\HasOne;
use CubeSystems\Leaf\Generator\Extras\Relation;
use CubeSystems\Leaf\Generator\GeneratorFormatter;
use CubeSystems\Leaf\Generator\Schema;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait CompilesRelations
{
    /**
     * @var StubRegistry
     */
    protected $stubRegistry;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @param Collection $fields
     * @return Collection
     */
    protected function compileRelationsMethods( Collection $fields ): Collection
    {
        return $fields->map( function( Relation $relation )
        {
            $name = class_basename( $relation->getModel() );

            return $this->stubRegistry->make( 'generator.method.relation', [
                'methodName' => Str::camel( $name ),
                'relationMethod' => $this->getModelRelationMethod( $relation->getFieldType() ),
                'modelClass' => ucfirst( $name ),
                'relationName' => 'owner'
            ] );
        } );
    }

    /**
     * @return Collection
     */
    protected function getSchemaRelationFields(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return sprintf(
                '$table->integer( \'%s_id\' )->nullable();',
                $this->formatter->field( class_basename( $relation->getModel() ) )
            );
        } );
    }

    /**
     * @return Collection
     */
    protected function getFillableRelationFields(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return '\'' . $this->formatter->field( class_basename( $relation->getModel() ) ) . '_id\',';
        } );
    }

    /**
     * @return Collection
     */
    protected function getUseRelations(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getModel() );
        } )->unique();
    }

    /**
     * @return Collection
     */
    protected function getUseRelationFields(): Collection
    {
        return $this->schema->getRelations()->map( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getFieldType() );
        } )->unique();
    }

    /**
     * @param string $fieldType
     * @return string
     */
    protected function getModelRelationMethod( string $fieldType ): string
    {
        $map = [
            HasOne::class => 'hasOne',
            HasMany::class => 'hasMany',
        ];

        return $map[ $fieldType ];
    }
}