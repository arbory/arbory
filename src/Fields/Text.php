<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Inputs\Input;
use CubeSystems\Leaf\Html\Html;

/**
 * Class Text
 * @package CubeSystems\Leaf\Fields
 */
class Text extends AbstractField
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
            ->setValue( $this->getValue() )
            ->addClass( 'text' );

        return Html::div()
            ->append( Html::div( $input->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ) )
            ->append( Html::div( $input )->addClass( 'value' ) )
            ->addClass( 'field type-text' );
    }
}
