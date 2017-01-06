<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BelongsTo
 * @package CubeSystems\Leaf\Fields
 */
class BelongsTo extends AbstractField
{
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        $label = Html::label( $this->getLabel() )->addAttributes( [ 'for' => $this->getName() ] );
        $select = Html::select( $this->getOptions() )->setName( $this->getNameSpacedName() );

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( $select )->addClass( 'value' )
        ] )->addClass( 'field type-item' );
    }

    /**
     * @return Model
     */
    protected function getRelatedModel()
    {
        return $this->getModel()->{$this->getName()}()->getRelated();
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        $selected = $this->getValue()
            ? $this->getValue()->getKey()
            : null;

        foreach( $this->getRelatedModel()->all() as $item )
        {
            $option = Html::option( (string) $item )->setValue( $item->getKey() );

            if( $selected === $item->getKey() )
            {
                $option->select();
            }

            $options[] = $option;
        }

        return $options;
    }
}
