<?php

namespace CubeSystems\Leaf\Builder;

use Closure;
use CubeSystems\Leaf\Fields\AbstractField;
use CubeSystems\Leaf\Results\FormResult;
use Validator;

/**
 * Class FormBuilder
 * @package CubeSystems\Leaf\Builder
 */
class FormBuilder extends AbstractBuilder
{
    const CONTEXT_CREATE = 'create';
    const CONTEXT_EDIT = 'edit';

    /**
     * @var integer
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $context;

    /**
     * @return integer
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param integer $identifier
     * @return $this
     */
    public function setIdentifier( $identifier )
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return $this
     */
    public function setContext( $context )
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        $class = $this->getResource();

        if( $this->getIdentifier() )
        {
            return $class::find( $this->getIdentifier() );
        }

        return new $class;
    }

    /**
     * @return FormResult
     */
    public function build()
    {
        $class = $this->getResource();

        switch( $this->getContext() )
        {
            case static::CONTEXT_EDIT:
                $model = $class::find( $this->getIdentifier() );
                break;

            default:
            case static::CONTEXT_CREATE:
                $model = new $class;
                break;
        }

        $result = new FormResult( $this );

        foreach( $this->getScheme()->getFields() as $field )
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

        $scheme = $this->getScheme();
        $controller = $this->getController();

        $model = $this->getResource();
        $primaryKey = $this->getModel()->getKeyName();

        foreach( $scheme->getFields() as $field )
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

        $this->setIdentifier( $model->{$primaryKey} );

//        foreach( $scheme->getFields() as $field )
//        {
//            $field->postUpdate( $input ); // TODO:
//        }

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

        $model = $this->getResource();
        $controller = $this->getController();

        foreach( $this->getScheme()->getFields() as $field )
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
            $controller->update( $input );
        }
        else
        {
            $model::find( $this->getIdentifier() )
                ->update( $input );
        }

//        foreach( $this->getScheme()->getFields() as $field )
//        {
//            $field->postUpdate( $input );
//        }

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

        $model = $this->getResource();
        $model = $model::find( $this->getIdentifier() );

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
