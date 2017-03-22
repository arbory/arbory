<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Admin\Widgets\Select;
use CubeSystems\Leaf\Admin\Form\Fields\FieldInterface;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class OptionFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class OptionFieldRenderer extends InputFieldRenderer
{
    /**
     * @return Element
     */
    protected function getLabel()
    {
        return Html::label( $this->field->getLabel() )->addAttributes( [ 'for' => $this->field->getName() ] );
    }

    /**
     * @return Element
     */
    protected function getInput()
    {
        return Select::create()
            ->options( $this->field->getOptions() )
            ->selected( $this->field->getModel()->getKey() )
            ->name( $this->field->getNameSpacedName() )
            ->render();
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div( [
            Html::div( $this->getLabel() )->addClass( 'label-wrap' ),
            Html::div( $this->getInput() )->addClass( 'value' )
        ] )->addClass( 'field type-item' );
    }
}

