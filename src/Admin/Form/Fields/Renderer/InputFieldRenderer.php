<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Inputs\AbstractInputField;
use CubeSystems\Leaf\Html\Html;

/**
 * Class InputFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class InputFieldRenderer extends BaseRenderer
{
    /**
     * @return AbstractInputField
     */
    protected function getInput()
    {
        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( $this->field->getValue() )
            ->addClass( 'text' );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $input = $this->getInput();
        $label = $input->getLabel( $this->field->getLabel() );

        return $this->buildField( $label, $input );
    }
}
