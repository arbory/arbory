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

        $fieldSet->add( new Hidden( $model->getKeyName() ) )
            ->setValue( $model->getKey() )
            ->setInputNamespace( $inputNamespace );

        if( $this->canRemoveRelationItems() )
        {
            $fieldSet->add( new RemoveRelationItem( '_destroy' ) )
                ->setValue( 'false' )
                ->setInputNamespace( $inputNamespace );
        }

        $builder = new FormBuilder( $model );
        $builder->setFieldSet( $fieldSet );
        $builder->setController( $this->getController() );

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
        $fieldSet = new FieldSet;
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        return $fieldSet;
    }
}
