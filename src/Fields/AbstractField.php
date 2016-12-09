<?php

namespace CubeSystems\Leaf\Fields;

use Closure;
use CubeSystems\Leaf\Http\Controllers\Admin\AbstractCrudController;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

/**
 * Class AbstractField
 * @package CubeSystems\Leaf\Fields
 */
abstract class AbstractField implements FieldInterface
{
    const CONTEXT_LIST = 'list';
    const CONTEXT_FORM = 'form';

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
     * @var string
     */
    protected $context;

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var AbstractCrudController
     */
    protected $controller;

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
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return $this
     */
    public function setContext( $context )
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return $this
     */
    public function setListContext()
    {
        $this->context = self::CONTEXT_LIST;

        return $this;
    }

    /**
     * @return $this
     */
    public function setFormContext()
    {
        $this->context = self::CONTEXT_FORM;

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
     * @return string
     */
    public function getViewName()
    {
        return 'leaf::builder.fields.' . snake_case( class_basename( static::class ) );
    }

    /**
     * @return bool
     */
    public function isForForm()
    {
        return $this->getContext() === self::CONTEXT_FORM;
    }

    /**
     * @return bool
     */
    public function isForList()
    {
        return $this->getContext() === self::CONTEXT_LIST;
    }

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
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel( $model )
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return AbstractCrudController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param AbstractCrudController $controller
     * @return $this
     */
    public function setController( $controller )
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function beforeModelSave( Model $model, array $input = [] )
    {

    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterModelSave( Model $model, array $input = [] )
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
     * TODO: Move to trait
     */

    protected $inputNamespace;

    public function getInputName()
    {
        $nameParts = preg_split( '/\./', $this->inputNamespace, NULL, PREG_SPLIT_NO_EMPTY );
        $nameParts[] = $this->getName();

        return 'resource[' . implode( '][', $nameParts ) . ']';
    }

    public function getInputId()
    {
        return strtr( $this->getInputName(), [ '[' => '_', ']' => '' ] );
    }

    public function getInputNamespace()
    {
        return $this->inputNamespace;
    }

    public function setInputNamespace( $namespace )
    {
        $this->inputNamespace = $namespace;

        return $this;
    }

    /**
     * @return View
     */
    abstract public function render();

}
