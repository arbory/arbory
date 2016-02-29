<?php

namespace CubeSystems\Leaf\Fields;

use Closure;
use CubeSystems\Leaf\Results\Row;
use CubeSystems\Leaf\Scheme;

/**
 * Interface FieldInterface
 * @package CubeSystems\Leaf\Fields
 */
interface FieldInterface
{
    /**
     * FieldInterface constructor.
     * @param $name string
     */
    public function __construct( $name );

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name string
     * @return $this
     */
    public function setName( $name );

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param $value string
     * @return $this
     */
    public function setValue( $value );

    /**
     * @return Row
     */
    public function getRow();

    /**
     * @param $row Row
     * @return $this
     */
    public function setRow( Row $row );

    /**
     * @return string
     */
    public function getContext();

    /**
     * @param $context string
     * @return $this
     */
    public function setContext( $context );

    /**
     * @return $this
     */
    public function setListContext();

    /**
     * @return $this
     */
    public function setFormContext();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label );

    /**
     * @return string
     */
    public function getViewName();

    /**
     * @return bool
     */
    public function isForForm();

    /**
     * @return bool
     */
    public function isForList();

    /**
     * @return \Closure|string
     */
    public function getBefore();

    /**
     * @param \Closure|string
     * @return $this
     */
    public function setBefore( $before );

    /**
     * @return bool
     */
    public function hasBefore();

    /**
     * @return Scheme
     */
    public function getScheme();

    /**
     * @param Scheme $scheme
     * @return $this
     */
    public function setScheme( Scheme $scheme );

    /**
     * @return Closure
     */
    public function getSaveWith();

    /**
     * @param Closure $handler
     * @return $this
     */
    public function setSaveWith( Closure $handler );

    /**
     * @return bool
     */
    public function hasSaveWith();

    /**
     * @return string
     */
    public function render();
}
