<?php

namespace CubeSystems\Leaf\Fields\Renderer;

use CubeSystems\Leaf\Fields\FieldInterface;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class InputFieldRenderer
 * @package CubeSystems\Leaf\Fields\Renderer
 */
class InputFieldRenderer
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
        $input = Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( $this->field->getValue() )
            ->addClass( 'text' );

        $label = $input->getLabel( $this->field->getLabel() );

        return Html::div()
            ->append( Html::div( $label )->addClass( 'label-wrap' ) )
            ->append( Html::div( $input )->addClass( 'value' ) )
            ->addClass( 'field type-text' );
    }
}
