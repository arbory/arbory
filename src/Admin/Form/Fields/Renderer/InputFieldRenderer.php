<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\AbstractInputField;
use Arbory\Base\Html\Html;

/**
 * Class InputFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
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
