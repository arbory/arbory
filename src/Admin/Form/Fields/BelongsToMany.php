<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BelongsToMany
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class BelongsToMany extends AbstractField
{
    /**
     * @return string
     */
    public function __toString()
    {
        $list = Html::ul();

        foreach( $this->getValue() as $item )
        {
            $list->append( Html::li( $item ) );
        }

        return (string) $list;
    }

    /**
     * @return bool
     */
    public function isSortable(  )
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * @return Element
     */
    public function render()
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
                ->setValue( 1 );

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
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {

    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        $relation = $this->getRelation();

        $submittedIds = $request->input( $this->getNameSpacedName(), [] );
        $existingIds = $this->getModel()->getAttribute( $this->getName() )
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
