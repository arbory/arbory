<?php

namespace CubeSystems\Leaf\Admin\Form;

use CubeSystems\Leaf\Admin\Form\Fields\AbstractField;
use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Admin\Form\Fields\HasMany;
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
        $inputNameParts = explode( '.', $inputName );

        array_shift( $inputNameParts );

        $fields = [];

        /**
         * @var FieldSet $previousFieldSet
         * @var AbstractField $previousField
         */
        $previousFieldSet = null;
        $previousField = null;

        foreach( $inputNameParts as $index => $fieldName )
        {
            $field = null;

            if( $previousFieldSet )
            {
                if( is_numeric( $fieldName ) && $previousField instanceof HasMany )
                {
                    /** @var HasMany $previousField */
                    $nested = $previousField->getValue();

                    if( $nested )
                    {
                        $resource = $nested->get( $fieldName );

                        if ( !$resource )
                        {
                            continue;
                        }

                        /**
                         * @var Collection $nested
                         * @var FieldSet $fieldSet
                         */
                        $previousFieldSet = $previousField->getRelationFieldSet( $resource, $fieldName );

                        continue;
                    }
                }
                else
                {
                    $field = $previousFieldSet->getFieldByName( $fieldName );
                }
            }
            else
            {
                $field = $this->getFieldByName( $fieldName );
            }

            if( $field )
            {
                $previousField = $field;
                $previousFieldSet = $previousField->getFieldSet();

                $fields[ $fieldName ] = $field;
            }
        }

        return $fields;
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
