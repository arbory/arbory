<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Arbory\Base\Nodes\Node;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

/**
 * Class HasOne
 * @package Arbory\Base\Admin\Form\Fields
 */
class HasOne extends AbstractRelationField
{
    /**
     * @return Element
     */
    public function render()
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()->addClass( 'section content-fields' );

        foreach( $this->getRelationFieldSet( $item )->getFields() as $field )
        {
            $block->append( $field->render() );
        }

        return $block;
    }

    /**
     * @param Model $relatedModel
     * @return FieldSet
     */
    public function getRelationFieldSet( Model $relatedModel )
    {
        $fieldSet = new FieldSet( $relatedModel, $this->getNameSpacedName() );
        $fieldSetCallback = $this->fieldSetCallback;

        $fieldSetCallback( $fieldSet );

        $fieldSet->add( new Hidden( $relatedModel->getKeyName() ) )
            ->setValue( $relatedModel->getKey() );

        return $fieldSet;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {

    }

    /**
     * @param Request $request
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function afterModelSave( Request $request )
    {
        $relatedModel = $this->getValue() ?: $this->getRelatedModel();
        $relation = $this->getRelation();

        foreach ($this->getRelationFieldSet($relatedModel)->getFields() as $field) {
            $field->beforeModelSave( $request );
        }

        if ($relation instanceof MorphOne) {
            $polymorphicFields = [
                $relation->getMorphType() => get_class( $relation->getParent() ),
                $relation->getForeignKeyName() => $relation->getParent()->{$relatedModel->getKeyName()}
            ];
            $relatedModel->fill($polymorphicFields)->save();

        } elseif ($relation instanceof MorphTo) {
            $relatedModel->save();

            $this->getModel()->fill( [
                $relation->getMorphType() => get_class( $relatedModel ),
                $relation->getForeignKey() => $relatedModel->{$relatedModel->getKeyName()},
            ] )->save();
        }  elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
            $relatedModel->save();

            $this->getModel()->setAttribute( $relation->getForeignKey(), $relatedModel->getKey() );
            $this->getModel()->save();
        } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
            $relatedModel->save();

            $localKey = explode( '.', $relation->getQualifiedParentKeyName() )[1];

            $this->getModel()->setAttribute( $localKey, $relatedModel->getKey() );
            $this->getModel()->save();
        }

        foreach ($this->getRelationFieldSet( $relatedModel )->getFields() as $field) {
            $field->afterModelSave( $request );
        }
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];

        $relation = $this->getRelation();

        if( $relation instanceof MorphTo )
        {
            $model = clone $this->fieldSet->getModel();

            $model->setAttribute( $relation->getMorphType(), request()->input( $this->getFieldSet()->getNamespace() . '.' . $relation->getMorphType() ) );
            $model->setAttribute( $relation->getForeignKey(), 0 );

            $relatedModel = $model->{$this->getName()}()->getRelated();
        }
        else
        {
            $relatedModel = $this->getValue() ?: $this->getRelatedModel();
        }

        foreach( $this->getRelationFieldSet( $relatedModel )->getFields() as $field )
        {
            $rules = array_merge( $rules, $field->getRules() );
        }

        return $rules;
    }
}
