<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Interface FieldInterface
 * @package Arbory\Base\Admin\Form\Fields
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
     * @param string $rules
     * @return FieldInterface
     */
    public function rules( string $rules ): FieldInterface;

    /**
     * @return array
     */
    public function getRules(): array;

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
