<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;

class InputControl extends AbstractControl
{
    public function element(): Element
    {
        $input = Html::input();
        $input->setValue($this->getValue());

        return $this->applyAttributesAndClasses($input);
    }

    /**
     * @return Element|Input
     */
    public function render(Element $element)
    {
        return $element;
    }
}
