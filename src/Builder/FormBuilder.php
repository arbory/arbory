<?php

namespace CubeSystems\Leaf\Builder;

use CubeSystems\Leaf\Fields\AbstractField;
use CubeSystems\Leaf\Fields\FieldInterface;
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
        $result = new FormResult;

        foreach( $this->getFieldSet()->getFields() as $field )
        {
            $item = clone $field;
            $item
                ->setContext( AbstractField::CONTEXT_FORM )
                ->setModel( $model )
                ->setController( $this->getController() );

            $result->push( $item );
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

        $model = $this->getModel();
        $fieldSet = $this->getFieldSet();
        $controller = $this->getController();

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

        $fieldSet->triggerUpdate( $model, $input );

        if( method_exists( $controller, 'creating' ) )
        {
            $model = $controller->creating( $input );
        }
        else
        {
            $model->fill( $input )->save();
        }

        $this->setModel( $model );

        $fieldSet->triggerAfterSave( $model, $input );

        if( method_exists( $controller, 'afterCreate' ) )
        {
            return $controller->afterCreate( $model, $input );
        }

        $model->push();

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
        $fieldSet = $this->getFieldSet();
        $controller = $this->getController();

        $fieldSet->each( function ( FieldInterface $field ) use ( $model ) {
            $field->setModel( $model );
        } );

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

        $fieldSet->triggerUpdate( $model, $input );

        if( method_exists( $controller, 'updating' ) )
        {
            $controller->updating( $model, $input );
        }
        else
        {
            $model->fill( $input )->push();
        }

        $fieldSet->triggerAfterSave( $model, $input );

        if( method_exists( $controller, 'afterUpdate' ) )
        {
            return $controller->afterUpdate( $model, $input );
        }

        $model->push();

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
