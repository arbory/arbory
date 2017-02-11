<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRelationField extends AbstractField
{
    protected $fieldSetCallback;

    /**
     * AbstractRelationField constructor.
     * @param string $name
     * @param callable $fieldSetCallback
     */
    public function __construct( $name, callable $fieldSetCallback )
    {
        parent::__construct( $name );

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

        $builder = new FormBuilder( $model );
        $builder->setFieldSet( $fieldSet );
//        $builder->setController( $this->getController() );

        return $builder;
    }

    /**
     * @param FieldSet $fieldSet
     * @param $namespace
     * @return FieldSet
     */
    protected function getNamespacedFieldSet( FieldSet $fieldSet, $namespace )
    {
        // TODO: Move namespace to FieldSet
        foreach( $fieldSet->getFields() as $field )
        {
            $field->setInputNamespace( $namespace );
        }

        return $fieldSet;
    }

    /**
     * @return FieldSet
     */
    public function getRelationFieldSet()
    {
        $fieldSet = new FieldSet;
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        return $fieldSet;
    }
}
