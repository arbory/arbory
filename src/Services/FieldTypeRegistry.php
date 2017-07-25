<?php

namespace Arbory\Base\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FieldTypeRegistry
{
    /**
     * @var Collection
     */
    protected $fieldsByType;

    /**
     * @var Collection
     */
    protected $fieldsByRelation;

    /**
     * @var Collection
     */
    protected $fieldTypeHints;

    public function __construct()
    {
        $this->fieldsByType = new Collection();
        $this->fieldsByRelation = new Collection();
        $this->fieldTypeHints = new Collection();
    }

    /**
     * @param string $databaseType
     * @param string $fieldClass
     * @return void
     */
    public function registerByType( string $databaseType, string $fieldClass, string $typeHint = null )
    {
        $this->fieldsByType->put( $databaseType, $fieldClass );

        if( $typeHint )
        {
            $this->fieldTypeHints->put( $fieldClass, $typeHint );
        }
    }

    /**
     * @param string $tableName
     * @param string $fieldClass
     * @return void
     */
    public function registerByRelation( string $tableName, string $fieldClass )
    {
        $this->fieldsByRelation->put( $tableName, $fieldClass );
    }

    /**
     * @param string $fieldName
     * @param string $fieldType
     * @return string
     */
    public function resolve( string $fieldName, string $fieldType )
    {
        if( Str::contains( $fieldName, '.' ) )
        {
            list( $tableName, $tableFieldName ) = explode( '.', $fieldName );

            return $this->findByRelationName( $tableName );
        }

        return $this->findByTypeName( $fieldType );
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function findByRelationName( string $name )
    {
        return $this->fieldsByRelation->get( $name );
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function findByTypeName( string $name )
    {
        return $this->fieldsByType->get( $name );
    }

    /**
     * @param string $fieldType
     * @return string
     */
    public function getFieldTypeHint( string $fieldType )
    {
        $typeHint = $this->fieldTypeHints->get( $fieldType );

        return $typeHint ?: 'int';
    }

    /**
     * @return Collection
     */
    public function getFieldsByType(): Collection
    {
        return $this->fieldsByType;
    }

    /**
     * @return Collection
     */
    public function getFieldsByRelation(): Collection
    {
        return $this->fieldsByRelation;
    }
}
