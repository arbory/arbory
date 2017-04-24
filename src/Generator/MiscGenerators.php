<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Generator\Extras\Field;
use CubeSystems\Leaf\Generator\Extras\Relation;
use CubeSystems\Leaf\Services\StubRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class MiscGenerators
{
    /**
     * @var StubRegistry
     */
    protected $registry;

    /**
     * @var GeneratorFormatter
     */
    protected $formatter;

    /**
     * @param StubRegistry $registry
     * @param GeneratorFormatter $formatter
     */
    public function __construct(
        StubRegistry $registry,
        GeneratorFormatter $formatter
    )
    {
        $this->registry = $registry;
        $this->formatter = $formatter;
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    public function getFillable( Collection $items ): Collection
    {
        return $items->transform( function( Field $field )
        {
            return '\'' . $this->formatter->field( $field->getName() ) . '\',';
        } );
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    public function getFieldSet( Collection $items ): Collection
    {
        return $items->transform( function( Field $field )
        {
            return $this->registry->make( 'field', [
                'fieldClass' => $field->getClassName(),
                'fieldName' => $field->getName()
            ] );
        } );
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    public function getRelationFieldSet( Collection $items ): Collection
    {
        return $items->transform( function( Relation $relation )
        {
            $fieldReflection = new ReflectionClass( $relation->getFieldType() );
            $modelReflection = new ReflectionClass( $relation->getModel() );

            return $this->registry->make( 'field_relation', [
                'relationFieldClass' => $fieldReflection->getShortName(),
                'relationName' => Str::lower( $modelReflection->getShortName() ),
                'fields' => ''
            ] );
        } );
    }

    /**
     * @param Collection $items
     * @return Collection
     */
    public function getRelationMethods( Collection $items ): Collection
    {
        return $items->transform( function( Relation $relation )
        {
            $modelReflection = new ReflectionClass( $relation->getModel() );

            return $this->registry->make( 'model_relation_method', [
                'methodName' => Str::lower( $modelReflection->getShortName() ),
                'relationMethod' => 'morphMany',
                'modelClass' => $modelReflection->getShortName(),
                'relationName' => 'owner'
            ] );
        } );
    }

    /**
     * @param Collection $fields
     * @return Collection
     */
    public function getUseFields( Collection $fields )
    {
        return $fields->transform( function( Field $field )
        {
            return $this->formatter->use( $field->getType() );
        } )->unique();
    }
    
    /**
     * @param Collection $items
     * @return Collection
     */
    public function getUseRelationFields( Collection $items ): Collection
    {
        return $items->transform( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getFieldType() );
        } )->unique();
    }

    /**
     * @param Collection $relations
     * @return Collection
     */
    public function getUseRelations( Collection $relations )
    {
        return $relations->transform( function( Relation $relation )
        {
            return $this->formatter->use( $relation->getModel() );
        } )->unique();
    }
}