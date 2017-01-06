<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Html\Html;
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


//            $button = Html::button( Html::i()->addClass('fa fa-trash-o') )
//                ->addClass('button only-icon danger remove-nested-item')
//                ->addAttributes(['title' => trans('leaf.fields.relation.remove')]);
//
//            $input = Html::input()
//                ->setType( 'hidden' )
//                ->setName( $this->getInputNamespace() + [ '_destroy' ] )
//                ->setValue( 'false' )
//                ->addClass( 'destroy' );
//
//            Html::div([ $button, $input ])->addClass('remove-item-box');


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
