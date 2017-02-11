<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\CRUD\Resource;
use CubeSystems\Leaf\CRUD\ResourceFieldSet;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class AbstractField
 * @package CubeSystems\Leaf\Fields
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var ResourceFieldSet
     */
    protected $fieldSet;

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * AbstractField constructor.
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->setName( $name );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpacedName()
    {
        return $this->getResource()->getNamespace() . '.' . $this->getName();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if( $this->value === null )
        {
            $this->value = $this->getModel()->getAttribute( $this->getName() );
        }

        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue( $value )
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if( $this->label === null )
        {
            return $this->name;
        }

        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label )
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ResourceFieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param ResourceFieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( ResourceFieldSet $fieldSet )
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->getResource()->getModel();
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Resource $resource
     * @return $this
     */
    public function setResource( $resource )
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @param Builder $query
     * @param $string
     */
    public function searchConditions( Builder $query, $string )
    {
        if( !$this->isSearchable() )
        {
            return;
        }

        $query->where( $this->getName(), 'LIKE', "$string%", 'OR' );
    }
    
    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $this->getModel()->setAttribute( $this->getName(), $request->input( $this->getNameSpacedName() ) );
    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {

    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return true;
    }

    /**
     * @return View
     */
    abstract public function render();

}
