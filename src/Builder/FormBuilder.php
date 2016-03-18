<?php

namespace CubeSystems\Leaf\Builder;

use Closure;
use CubeSystems\Leaf\Fields\AbstractField;
use CubeSystems\Leaf\Results\FormResult;
use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * Class FormBuilder
 * @package CubeSystems\Leaf\Builder
 */
class FormBuilder extends AbstractBuilder
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * FormBuilder constructor.
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->setModel( $model );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel( Model $model )
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return FormResult
     */
    public function build()
    {
        $model = $this->getModel();
        $result = new FormResult( $this );

        foreach( $this->getFieldSet()->getFields() as $field )
        {
            $item = clone $field;
            $name = $item->getName();

            $value = $model->{$name};

            if( $item->hasBefore() )
            {
                $before = $item->getBefore();

                if( $before instanceof Closure )
                {
                    $value = $before( $value );
                }
                else
                {
                    $value = $before;
                }
            }

            $item
                ->setContext( AbstractField::CONTEXT_FORM )
                ->setValue( $value );

            $result->addField( $item );
        }

        return $result;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function create( array $input )
    {
        $input = array_get( $input, 'resource' );

        $fieldSet = $this->getFieldSet();
        $controller = $this->getController();
        $model = $this->getModel();

        foreach( $fieldSet->getFields() as $field )
        {
            if( $field->hasSaveWith() )
            {
                $saveFunction = $field->getSaveWith();
                $input[$field->getName()] = $saveFunction( $input[$field->getName()] );
            }
        }

        if( method_exists( $controller, 'beforeCreate' ) )
        {
            $controller->beforeCreate( $input );
        }

        if( property_exists( $model, 'rules' ) )
        {
            $validator = Validator::make( $input, $model::$rules );

            if( $validator->fails() )
            {
                return $validator;
            }
        }

        if( method_exists( $controller, 'creating' ) )
        {
            $model = $controller->creating( $input );
        }
        else
        {
            $model = $model::create( $input );
        }

        $this->setModel( $model );

        foreach( $fieldSet->getFields() as $field )
        {
            $field->postUpdate( $model, $input ); // TODO:
        }

        if( method_exists( $controller, 'afterCreate' ) )
        {
            return $controller->afterCreate( $input );
        }

        return null;
    }

    /**
     * @param array $input
     * @return $this
     */
    public function update( array $input )
    {
        $input = array_get( $input, 'resource' );

        $model = $this->getModel();
        $controller = $this->getController();

        foreach( $this->getFieldSet()->getFields() as $field )
        {
            if( $field->hasSaveWith() )
            {
                $saveFunction = $field->getSaveWith();
                $input[$field->getName()] = $saveFunction( $input[$field->getName()] );
            }
        }

        if( method_exists( $controller, 'beforeUpdate' ) )
        {
            $controller->beforeUpdate( $input );
        }

        if( property_exists( $model, 'rules' ) )
        {
            $validator = Validator::make( $input, $model::$rules );

            if( $validator->fails() )
            {
                return $validator;
            }
        }

        if( method_exists( $controller, 'updating' ) )
        {
            $controller->updating( $model, $input );
        }
        else
        {
            $model->update( $input );
        }

        foreach( $this->getFieldSet()->getFields() as $field )
        {
            $field->postUpdate( $model, $input );
        }

        if( method_exists( $controller, 'afterUpdate' ) )
        {
            return $controller->afterUpdate( $input );
        }

        return null;
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function destroy()
    {
        $controller = $this->getController();

        $model = $this->getModel();

        if( method_exists( $controller, 'beforeDelete' ) )
        {
            $controller->beforeDelete( $model );
        }

        if( method_exists( $controller, 'deleting' ) )
        {
            $controller->deleting( $model );
        }
        else
        {
            $model->delete();
        }

        if( method_exists( $controller, 'afterDelete' ) )
        {
            return $controller->afterDelete( $model );
        }

        return null;
    }
}
