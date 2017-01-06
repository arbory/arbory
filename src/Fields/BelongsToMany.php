<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;

class BelongsToMany extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        if( $this->isForList() )
        {
            return $this->renderListField();
        }
        elseif( $this->isForForm() )
        {
            return $this->renderFormField();
        }

        return null;
    }

    /**
     * @return Element
     */
    protected function renderListField()
    {
        $list = Html::ul();

        foreach( $this->getValue() as $item )
        {
            $list->append( Html::li( $item ) );
        }

        return $list;
    }

    /**
     * @return Element
     */
    protected function renderFormField()
    {
        $relatedModel = $this->getRelatedModel();
        $checkboxes = $this->getRelatedModelOptions( $relatedModel );

        $label = Html::label( $this->getLabel() )->addAttributes( [ 'for' => $this->getName() ] );

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( $checkboxes )->addClass( 'value' )
        ] )->addClass( 'field type-associated-set' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    private function getRelation()
    {
        return $this->getModel()->{$this->getName()}();
    }

    /**
     * @return Model
     */
    private function getRelatedModel()
    {
        return $this->getRelation()->getRelated();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $relatedModel
     * @return Element[]
     */
    public function getRelatedModelOptions( $relatedModel )
    {
        $checkboxes = [];

        $selectedOptions = $this->getValue()->pluck( $relatedModel->getKeyName() )->all();

        foreach( $relatedModel::all() as $modelOption )
        {
            $name = [
                'resource',
                $this->getName(),
                $modelOption->getKey()
            ];

            $checkbox = Html::checkbox()
                ->setName( implode( '.', $name ) )
                ->setValue(1);

            $checkbox->append( $checkbox->getLabel( (string) $modelOption ) );

            if( in_array( $modelOption->getKey(), $selectedOptions, true ) )
            {
                $checkbox->select();
            }

            $checkboxes[] = Html::div( $checkbox )->addClass( 'type-associated-set-item' );
        }

        return $checkboxes;
    }

    /**
     * @param Model $model
     * @param array $input
     */
    public function afterModelSave( Model $model, array $input = [] )
    {
        $relation = $this->getRelation();

        $submittedIds = array_get( $input, $this->getName(), [] );
        $existingIds = $model->getAttribute( $this->getName() )
            ->pluck( $this->getRelatedModel()->getKeyName() )
            ->toArray();

        foreach( $existingIds as $id )
        {
            if( !array_key_exists( $id, $submittedIds ) )
            {
                $relation->detach( $id );
            }
        }

        foreach( array_keys( $submittedIds ) as $id )
        {
            if( !in_array( $id, $existingIds, true ) )
            {
                $relation->attach( $id );
            }
        }
    }
}
