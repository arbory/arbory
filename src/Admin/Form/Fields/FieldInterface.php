<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface FieldInterface
 * @package CubeSystems\Leaf\Admin\Form\Fields
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
    public function getNameSpacedName();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value string
     * @return $this
     */
    public function setValue( $value );

    /**
     * @param string $rules
     * @return $this
     */
    public function setRules( string $rules );

    /**
     * @return string|null
     */
    public function getRules();

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
     * @param Request $request
     */
    public function beforeModelSave( Request $request );

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request );

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Element|string
     */
    public function render();
}
