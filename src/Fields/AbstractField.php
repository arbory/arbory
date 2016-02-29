<?php

namespace CubeSystems\Leaf\Fields;

use Closure;
use CubeSystems\Leaf\Results\Row;
use CubeSystems\Leaf\Scheme;
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
     * @var Row
     */
    protected $row;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var Closure
     */
    protected $before;

    /**
     * @var Closure
     */
    protected $after;

    /**
     * @var Scheme
     */
    protected $scheme;

    /**
     * @var Closure
     */
    protected $saveWith;

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
     * @return Row
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param Row $row
     * @return $this
     */
    public function setRow( Row $row )
    {
        $this->row = $row;

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
     * @return Closure
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param $before
     * @return $this
     */
    public function setBefore( $before )
    {
        $this->before = $before;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasBefore()
    {
        return $this->before !== null;
    }

    /**
     * @return Scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param Scheme $scheme
     * @return $this
     */
    public function setScheme( Scheme $scheme )
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return Closure
     */
    public function getSaveWith()
    {
        return $this->saveWith;
    }

    /**
     * @param Closure $handler
     * @return $this
     */
    public function setSaveWith( Closure $handler )
    {
        $this->saveWith = $handler;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSaveWith()
    {
        return $this->saveWith !== null;
    }

    /**
     * @return View
     */
    abstract public function render();

}
