<?php

namespace CubeSystems\Leaf\Builder;

use CubeSystems\Leaf\Http\Controllers\Admin\AdminController;
use CubeSystems\Leaf\Results\ResultInterface;
use CubeSystems\Leaf\FieldSet;
use Eloquent;

/**
 * Class AbstractBuilder
 * @package CubeSystems\Leaf\Builder
 */
abstract class AbstractBuilder
{
    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var AdminController
     */
    protected $controller;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @return FieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet )
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    /**
     * @return string|Eloquent
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $resource
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
     * @return $this
     */
    public function setController( AdminController $controller )
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param null $key
     * @return array
     */
    public function getParameters( $key = null )
    {
        return array_get( (array) $this->parameters, $key );
    }

    /**
     * @param array $parameters
     */
    public function setParameters( array $parameters = [] )
    {
        $this->parameters = $parameters;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasParameter( $key )
    {
        return array_has( (array) $this->parameters, $key );
    }

    abstract public function build();
}
