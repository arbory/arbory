<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Results\FormResult;
use Illuminate\Database\Eloquent\Model;


/**
 * Class HasMany
 * @package CubeSystems\Leaf\Fields
 */
class HasMany extends AbstractRelationField
{
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

    /**
     * @return Element|string
     */
    public function render()
    {
        return Html::section( [
            $this->getHeader(),
            $this->getBody(),
            $this->getFooter(),
        ] )
            ->addClass( 'nested' )
            ->addAttributes( [
                'data-name' => $this->getName(),
                'data-releaf-template' => $this->getRelationFromTemplate(),
            ] );
    }

    /**
     * @return Element
     */
    protected function getHeader()
    {
        return Html::header( Html::h1( $this->getLabel() ) );
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $relationItems = [];

        foreach( $this->getValue() as $index => $item )
        {
            $relationItem = $this->buildRelationForm(
                $item,
                $this->getRelationFieldSet(),
                $this->getName() . '.' . $index
            )->build();

            $relationItems[] = $this->getRelationItemHtml( $relationItem, $index );
        }

        return Html::div( $relationItems )->addClass( 'body list' );
    }

    /**
     * @return Element|null
     */
    protected function getFooter()
    {
        if( !$this->canAddRelationItem() )
        {
            return null;
        }

        return Html::footer(
            Html::button( [
                Html::i()->addClass( 'fa fa-plus' ),
                trans( 'leaf::fields.has_many.add_item' ),
            ] )
                ->addClass( 'button with-icon primary add-nested-item' )
                ->addAttributes( [
                    'type' => 'button',
                    'title' => trans( 'leaf::fields.has_many.add_item' ),
                ] )
        );
    }

    /**
     * @param FormResult $formResult
     * @param integer $index
     * @return Element
     */
    protected function getRelationItemHtml( FormResult $formResult, $index )
    {
        $fieldSetHtml = Html::fieldset()
            ->addClass( 'item type-association' )
            ->addAttributes( [
                'data-name' => $this->getName(),
                'data-index' => $index
            ] );

        foreach( $formResult->getFields() as $field )
        {
            $fieldSetHtml->append( $field->render() );
            $fieldSetHtml->append(
                $this->getFieldSetRemoveButton( 'resource.' . $this->getName() . '.' . $index . '._destroy' )
            );
        }

        return $fieldSetHtml;
    }

    /**
     * @param string $name
     * @return Element|null
     */
    protected function getFieldSetRemoveButton( $name )
    {
        if( !$this->canRemoveRelationItems() )
        {
            return null;
        }

        $button = Html::button( Html::i()->addClass( 'fa fa-trash-o' ) )
            ->addClass( 'button only-icon danger remove-nested-item' )
            ->addAttributes( [
                'type' => 'button',
                'title' => trans( 'leaf::fields.relation.remove' ),
            ] );

        $input = Html::input()
            ->setType( 'hidden' )
            ->setName( $name )
            ->setValue( 'false' )
            ->addClass( 'destroy' );

        return Html::div( [ $button, $input ] )->addClass( 'remove-item-box' );
    }

    /**
     * @return Element
     */
    protected function getRelationFromTemplate()
    {
        $formResults = $this->buildRelationForm(
            $this->getModel()->{$this->getName()}()->getRelated(),
            $this->getRelationFieldSet(),
            $this->getName() . '._template_'
        )->build();

        return $this->getRelationItemHtml( $formResults, '_template_' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function getRelation()
    {
        return $this->getModel()->{$this->getName()}();
    }


    /**
     * @param Model $model
     * @param array $input
     * @return void
     */
    public function afterModelSave( Model $model, array $input = [] )
    {
        $this->setModel( $model );

        $relationsInput = (array) array_get( $input, $this->getName(), [] );

        foreach( $relationsInput as $relationVariables )
        {
            $this->processRelationItemUpdate( $relationVariables );
        }
    }

    /**
     * @param array $variables
     */
    private function processRelationItemUpdate( array $variables )
    {
        $variables[$this->getRelation()->getForeignKeyName()] = $this->getModel()->getKey();

        $relatedModel = $this->findRelatedModel( $variables );

        if( filter_var( array_get( $variables, '_destroy' ), FILTER_VALIDATE_BOOLEAN ) )
        {
            $relatedModel->delete();

            return;
        }

        $relatedModel->fill( $variables );
        $relatedModel->save();
    }

    /**
     * @param $variables
     * @return Model
     */
    private function findRelatedModel( $variables )
    {
        $relation = $this->getRelation();

        $relatedModelId = array_get( $variables, $relation->getRelated()->getKeyName() );

        return $relation->getRelated()->findOrNew( $relatedModelId );
    }
}
