<?php

namespace CubeSystems\Leaf\Fields;

use Closure;
use CubeSystems\Leaf\Http\Controllers\Admin\AbstractCrudController;
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
     * @return FieldSet
     */
    public function getFieldSet();

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet );

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
     * @return AbstractCrudController
     */
    public function getController();

    /**
     * @param AbstractCrudController $controller
     * @return $this
     */
    public function setController( $controller );

    /**
     * @param Model $model
     * @param array $input
     */
    public function beforeModelSave( Model $model, array $input = [] );

    /**
     * @param $model
     * @param $input
     */
    public function afterModelSave( Model $model, array $input = [] );

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render();
}
