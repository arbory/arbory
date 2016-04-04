<?php

namespace CubeSystems\Leaf;

use CubeSystems\Leaf\Fields\FieldFactory;
use CubeSystems\Leaf\Fields\FieldInterface;
use CubeSystems\Leaf\Http\Controllers\AdminController;

/**
 * Class FieldSet
 * @package CubeSystems\Leaf
 */
class FieldSet
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var AdminController
     */
    protected $controller;

    /**
     * @var FieldInterface[]|array
     */
    protected $fields = [ ];

    /**
     * FieldSet constructor.
     * @param $resource
     * @param $controller
     */
    public function __construct( $resource, $controller )
    {
        $this->setResource( $resource );
        $this->setController( $controller );
    }

    /**
     * @param $method
     * @param $arguments
     * @return FieldInterface
     */
    public function __call( $method, $arguments )
    {
        $name = reset( $arguments );

        $field = FieldFactory::getFieldByType( $method, $name );

        $this->add( $field );

        return $field;
    }

    /**
     * @param FieldInterface $field
     * @return FieldInterface
     */
    public function add( FieldInterface $field )
    {
        $field->setFieldSet( $this );
        $this->fields[$field->getName()] = $field;

        return $field;
    }

    /**
     * @return FieldInterface[]|array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $name
     * @return FieldInterface
     */
    public function getField( $name )
    {
        if( !$this->hasField( $name ) )
        {
            return null;
        }

        return $this->fields[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField( $name )
    {
        return array_key_exists( $name, $this->fields );
    }

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param $resource
     * @return $this
     */
    public function setResource( $resource )
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return AdminController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param AdminController $controller
     */
    public function setController( $controller )
    {
        $this->controller = $controller;
    }
}
