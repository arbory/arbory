<?php

namespace CubeSystems\Leaf\Results;

use CubeSystems\Leaf\Fields\FieldInterface;

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
     * @param FieldInterface $field
     */
    public function add( FieldInterface $field )
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
}
