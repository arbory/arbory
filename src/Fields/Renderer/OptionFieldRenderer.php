<?php

namespace CubeSystems\Leaf\Fields\Renderer;

use CubeSystems\Leaf\Fields\FieldInterface;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class OptionFieldRenderer
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * InputFieldRenderer constructor.
     * @param FieldInterface $field
     */
    public function __construct( FieldInterface $field )
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $label = Html::label( $this->field->getLabel() )->addAttributes( [ 'for' => $this->field->getName() ] );
        $select = Html::select( $this->field->getOptions() )->setName( $this->field->getNameSpacedName() );

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( $select )->addClass( 'value' )
        ] )->addClass( 'field type-item' );
    }
}

