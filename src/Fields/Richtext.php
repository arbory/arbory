<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class Richtext extends AbstractField
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        $textarea = Html::textarea($this->getValue());
        $textarea->setName( $this->getNameSpacedName() );
        $textarea->addClass( 'richtext' );
        $textarea->addAttributes([
            'rows' => 5,
            'cols' => 50,
            'data-attachment-upload-url' => null,
        ]);

        return Html::div()
            ->append( Html::div( $textarea->getLabel( $this->getLabel() ) )->addClass( 'label-wrap' ) )
            ->append( Html::div( $textarea )->addClass( 'value' ) )
            ->addClass( 'field type-richtext' );
    }
}
