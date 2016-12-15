<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Content;
use CubeSystems\Leaf\Html\Elements\Div;
use CubeSystems\Leaf\Html\Elements\Inputs\CheckBox;
use CubeSystems\Leaf\Html\Elements\Label;

class BelongsToMany extends BelongsTo
{
    protected function renderListField()
    {
        dump( __METHOD__ . ': List view not implemented yet' );
    }

    protected function renderFormField()
    {
        $relatedModel = $this->getRelatedModel();
        $checkboxes = $this->getRelatedModelOptions( $relatedModel );

        return (string) ( new Div )
            ->append( ( new Div( new Label( $this->getName(), $this->getLabel() ) ) )->addClass( 'label-wrap' ) )
            ->append( ( new Div( $checkboxes ) )->addClass( 'value' ) )
            ->addClass( 'field type-associated-set' );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $relatedModel
     * @return array|Div
     */
    public function getRelatedModelOptions( $relatedModel )
    {
        $checkboxes = new Content;

        $selected = $this->getValue()->pluck( $relatedModel->getKeyName() )->all();

        foreach( $relatedModel::all() as $modelOption )
        {
            $name = [
                'resource',
                $this->getName() . '_attributes',
                $modelOption->getKey()
            ];

            $checkbox = new CheckBox;
            $checkbox->setName(implode( '.', $name ));
            $checkbox->setValue(1);
            $checkbox->label( (string) $modelOption );

            if( in_array( $modelOption->getKey(), $selected, true ) )
            {
                $checkbox->select();
            }

            $checkboxes->push( ( new Div( $checkbox ) )->addClass( 'type-associated-set-item' ) );
        }

        return $checkboxes;
    }
}
