<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRelationField extends AbstractField
{
    protected $fieldSetCallback;

    public function __construct( $name, callable $fieldSetCallback ) // TODO: Setter
    {
        $this->setName( $name );
        $this->fieldSetCallback = $fieldSetCallback;
    }

    /**
     * @param Model $model
     * @param FieldSet $fieldSet
     * @param $inputNamespace
     * @return FormBuilder
     */
    protected function buildRelationForm( $model, $fieldSet, $inputNamespace )
    {
        $fieldSet = $this->getNamespacedFieldSet( $fieldSet, $inputNamespace );

        $fieldSet->add( new Hidden( 'id' ) ) // TODO: Use related model key name instead of 'ID'
        ->setValue( $model->{$model->getKeyName()} )
            ->setInputNamespace( $inputNamespace );

        if( $this->canRemoveRelationItems() )
        {
            $fieldSet->add( new RemoveRelationItem( '_destroy' ) )
                ->setValue( 'false' )
                ->setInputNamespace( $inputNamespace );
        }

        $builder = new FormBuilder( $model );
        $builder->setFieldSet( $fieldSet );
        $builder->setController( $this->getFieldSet()->getController() );

        return $builder;
    }

    // TODO: Move namespace to FieldSet
    protected function getNamespacedFieldSet( FieldSet $fieldSet, $namespace )
    {
        foreach( $fieldSet->getFields() as $field )
        {
            $field->setInputNamespace( $namespace );
        }

        return $fieldSet;
    }

    public function getRelationFieldSet()
    {
        $relatedModel = $this->getModel()->{$this->getName()}()->getRelated();
        $fieldSet = new FieldSet( get_class( $relatedModel ), $this->getFieldSet()->getController() );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        return $fieldSet;
    }
}
