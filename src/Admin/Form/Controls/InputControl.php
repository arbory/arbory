<?php


namespace Arbory\Base\Admin\Form\Controls;


use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class InputControl extends AbstractControl
{
    public function element():Element
    {
        $input = Html::input();
        $input->setValue($this->getValue());
        $input = $this->applyAttributesAndClasses($input);

        return $input;
    }

    /**
     * @return Element|\Arbory\Base\Html\Elements\Inputs\Input
     */
    public function render(Element $element)
    {
        return $element;
    }
}