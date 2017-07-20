<?php

namespace CubeSystems\Leaf\Admin\Form;

use CubeSystems\Leaf\Admin\Form\Fields\AbstractField;
use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Services\FieldSetFieldFinder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Waavi\Translation\Repositories\LanguageRepository;

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
     * @param string $inputName
     * @return AbstractField|null
     */
    public function findFieldByInputName( string $inputName )
    {
        $inputNameParts = explode( '.', $inputName );
        $fields = $this->findFieldsByInputName( $inputName );

        return array_get( $fields, end( $inputNameParts ) );
    }

    /**
     * @param string $inputName
     * @return array
     */
    public function findFieldsByInputName( string $inputName )
    {
        return ( new FieldSetFieldFinder( app( LanguageRepository::class ), $this ) )->find( $inputName );
    }

    /**
     * @param string $fieldName
     * @return AbstractField|null
     */
    public function getFieldByName( string $fieldName )
    {
        return $this->getFields()->first( function( AbstractField $field ) use ( $fieldName )
        {
            return $field->getName() === $fieldName;
        } );
    }

    /**
     * @param string $fieldName
     * @return Collection
     */
    public function getFieldsByName( string $fieldName )
    {
        $fields = [];

        foreach( $this->getFields()->toArray() as $field )
        {
            /** @var AbstractField $field */

            if( $field->getName() === $fieldName )
            {
                $fields[] = $field;
            }
        }

        return new Collection( $fields );
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
