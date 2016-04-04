<?php

namespace CubeSystems\Leaf\Results;

use CubeSystems\Leaf\Fields\FieldInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Row
 * @package CubeSystems\Leaf\Results
 */
class Row
{
    /**
     * @var \Eloquent|string
     */
    protected $resource;

    /**
     * @var FieldInterface[]|array
     */
    protected $fields = [ ];

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Row[]|Collection
     */
    protected $children;

    /**
     * @param FieldInterface $field
     */
    public function addField( FieldInterface $field )
    {
        $field->setRow( $this );
        $this->fields[$field->getName()] = $field;
    }

    /**
     * @return array|\CubeSystems\Leaf\Fields\FieldInterface[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $name
     * @return FieldInterface|null
     */
    public function getFieldByName( $name )
    {
        if( array_key_exists( $name, $this->fields ) )
        {
            return $this->fields[$name];
        }

        return null;
    }


    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource( $resource )
    {
        $this->resource = $resource;
    }

    /**
     * @param $identifier
     */
    public function setIdentifier( $identifier )
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel( Model $model )
    {
        $this->model = $model;
    }

    /**
     * @return Row[]|Collection
     */
    public function getChildRows()
    {
        return $this->children;
    }

    /**
     * @param Collection|Row[] $collection
     */
    public function setChildRows( Collection $collection )
    {
        $this->children = $collection;
    }

    /**
     * @return bool
     */
    public function hasChildRows()
    {
        return $this->children instanceof Collection && $this->children->count() > 0;
    }
}
