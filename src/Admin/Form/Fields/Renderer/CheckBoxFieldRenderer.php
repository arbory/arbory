<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\CheckBox;
use Arbory\Base\Html\Html;

/**
 * Class CheckBoxFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class CheckBoxFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'boolean';

    /**
     * @var \Arbory\Base\Admin\Form\Fields\Checkbox
     */
    protected $field;

    protected function getElement()
    {
        $input = parent::getElement();

        $input->setType('checkbox');

        if( $this->field->getValue() )
        {
            $input->addAttributes(['checked' => '']);
        }

        return $input;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $input = parent::render();

        return Html::div( [
            $input,
            $input->getLabel( $this->field->getLabel() )
        ] );
    }
}
