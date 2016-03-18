<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Model;

class HasMany extends AbstractField
{
    protected $fieldSetCallback;

    public function __construct( $name, callable $fieldSetCallback ) // TODO: Setter
    {
        $this->setName( $name );
        $this->fieldSetCallback = $fieldSetCallback;
    }

    public function canRemoveRelationItems()
    {
        // TODO: Setter, getter, permissions, etc.
        return true;
    }

    public function canAddRelationItem()
    {
        // TODO: Setter, getter, permissions, etc.
        return true;
    }

    public function render()
    {
        $resource = $this->getFieldSet()->getResource();
        $model = new $resource;

        $relatedModel = $model->{$this->getName()}()->getRelated();

        $fieldSet = new FieldSet( get_class( $relatedModel ), $this->getFieldSet()->getController() );
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback( $fieldSet );

        $relationItems = [ ];

        foreach( $this->getValue() as $index => $item )
        {
            $relationItems[] = $this->buildRelationForm(
                $item,
                clone $fieldSet,
                $this->getName() . '_attributes.' . $index
            )->build();
        }

        return view( $this->getViewName(), [
            'field' => $this,
            'relations' => $relationItems,
            'template' => $this->getRelationFromTemplate( $relatedModel, clone $fieldSet )
        ] );
    }

    /**
     * @param Model $model
     * @param FieldSet $fieldSet
     * @param $inputNamespace
     * @return FormBuilder
     */
    protected function buildRelationForm( $model, $fieldSet, $inputNamespace )
    {
        foreach( $fieldSet->getFields() as $field )
        {
            $field->setInputNamespace( $inputNamespace );
        }

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

    /**
     * @param $relatedModel
     * @param $fieldSet
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    protected function getRelationFromTemplate( $relatedModel, $fieldSet )
    {
        $formBuilder = $this->buildRelationForm(
            $relatedModel,
            clone $fieldSet,
            $this->getName() . '_attributes' . '._template_'
        );

        return view( $this->getViewName() . '_fieldset', [
            'name' => $this->getName(),
            'index' => '_template_',
            'fields' => $formBuilder->build()->getFields()
        ] )->render();
    }

    protected function getRelatedModel( $model )
    {
        return $model->{$this->getName()}();
    }

    /**
     * @param Model $model
     * @param array $input
     * @return void
     */
    public function postUpdate( Model $model, array $input = [ ] )
    {
        /**
         * @var $relation \Illuminate\Database\Eloquent\Relations\HasMany
         */
        $inputVariables = array_get( $input, $this->getName() . '_attributes' );

        if( !$inputVariables )
        {
            return;
        }

        $relation = $model->{$this->getName()}();
        $parentKeySegments = explode( '.', $relation->getQualifiedParentKeyName() );
        $relationParentKey = $parentKeySegments[count( $parentKeySegments ) - 1];
        $relationForeignKey = $relation->getPlainForeignKey();

        $relatedModel = $relation->getRelated();

        $relatedModelKeyName = $relatedModel->getKeyName();

        foreach( $inputVariables as $relationVariables )
        {
            $relationVariables[ $relationForeignKey ] = $model->{$relationParentKey};

            $deleteRelation = filter_var( array_get( $relationVariables, '_destroy' ), FILTER_VALIDATE_BOOLEAN );
            $relatedModelId = array_get( $relationVariables, $relatedModelKeyName );
            $relatedModel1 = $relatedModel->findOrNew( $relatedModelId );

            if( $deleteRelation === true )
            {
                $relatedModel1->delete();

                continue;
            }

            $relatedModel1->fill( $relationVariables );
            $relatedModel1->save();
        }
    }
}
