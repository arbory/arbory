<?php

namespace CubeSystems\Leaf\Admin\Form;

use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class FieldSet
 * @package CubeSystems\Leaf\Admin\Form
 */
class FieldSet extends Collection
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Resource constructor.
     * @param Model $model
     * @param string $namespace
     */
    public function __construct( Model $model, $namespace )
    {
        $this->namespace = $namespace;
        $this->model = $model;

        parent::__construct( [] );
    }

    /**
     * @return Collection|FieldInterface[]
     */
    public function getFields()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     *
     */
    public function getRules()
    {
        $rules = [];

        foreach( $this->all() as $field )
        {
            $rules = array_merge( $rules, $field->getRules() );
        }

        return $rules;
    }

    /**
     * @param FieldInterface $field
     * @param null $key
     * @return FieldSet|Collection
     */
    public function prepend( $field, $key = null )
    {
        $field->setFieldSet( $this );

        return parent::prepend( $field, $key );
    }

    /**
     * @param FieldInterface $field
     * @return FieldInterface
     */
    public function add( FieldInterface $field )
    {
        $this->push( $field );

        return $field;
    }

    /**
     * @param string $key
     * @param FieldInterface $field
     */
    public function offsetSet( $key, $field )
    {
        $field->setFieldSet( $this );

        parent::offsetSet( $key, $field );
    }

    /**
     * @return array|FieldInterface[]
     */
    public function all()
    {
        return parent::all();
    }

}
