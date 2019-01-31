<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\CheckBox;
use Arbory\Base\Html\Html;

/**
 * Class CheckBoxFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class CheckBoxFieldRenderer extends InputFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'boolean';

    /**
     * @var \Arbory\Base\Admin\Form\Fields\Checkbox
     */
    protected $field;

    /**
     * @return CheckBox
     */
    protected function getInput()
    {
        /** @var CheckBox $checkBox */
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

        return Html::div( [
                $input,
                $input->getLabel( $this->field->getLabel() )
            ] );
    }
}
