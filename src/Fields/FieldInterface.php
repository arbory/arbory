<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\CRUD\ResourceFieldSet;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
     * @param ResourceFieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( ResourceFieldSet $fieldSet );

    /**
     * @return Model
     */
    public function getModel();

    /**
     * @return Resource
     */
    public function getResource();

    /**
     * @param Resource $resource
     * @return $this
     */
    public function setResource( $resource );

    /**
     * @param Builder $query
     * @param $string
     */
    public function searchConditions( Builder $query, $string );

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request);

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request);

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render();
}
