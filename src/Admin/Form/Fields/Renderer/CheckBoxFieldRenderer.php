<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Elements\Inputs\CheckBox;
use CubeSystems\Leaf\Html\Html;

/**
 * Class CheckBoxFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class CheckBoxFieldRenderer extends InputFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'boolean';

    /**
     * @var \CubeSystems\Leaf\Admin\Form\Fields\Checkbox
     */
    protected $field;

    /**
     * @return CheckBox
     */
    protected function getInput()
    {
        $checkBox = Html::checkbox()->setName( $this->field->getNameSpacedName() );

        if( $this->field->getValue() )
        {
            $checkBox->select();
        }

        return $checkBox;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $input = $this->getInput();

        return Html::div(
            Html::div( [
                $input,
                $input->getLabel( $this->field->getLabel() )
            ] )->addClass( 'value' )
        )->addClass( 'field type-boolean' );
    }
}
