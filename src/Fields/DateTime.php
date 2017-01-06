<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Html;

class DateTime extends Text
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element|string
     */
    public function render()
    {
        $input = Html::input()
            ->setName( $this->getNameSpacedName() )
            ->setValue( date( 'Y-m-d', strtotime( $this->getValue() ) ) )
            ->addClass( 'text datetime-picker' );

        return Html::div()
            ->append( Html::div( $input->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ) )
            ->append( Html::div( $input )->addClass( 'value' ) )
            ->addClass( 'field type-text' );
    }
}
