<?php

namespace CubeSystems\Leaf\Fields;

use Closure;
use CubeSystems\Leaf\Http\Controllers\AdminController;
use CubeSystems\Leaf\Results\Row;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface FieldInterface
 * @package CubeSystems\Leaf\Fields
 */
interface FieldInterface
{
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
     * @return FieldSet
     */
    public function getFieldSet();

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet );

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
     * @return Model
     */
    public function getModel();

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel( $model );

    /**
     * @return AdminController
     */
    public function getController();

    /**
     * @param AdminController $controller
     * @return $this
     */
    public function setController( $controller );

    /**
     * @return string
     */
    public function render();
}
