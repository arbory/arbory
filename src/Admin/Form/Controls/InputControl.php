<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class InputControl extends AbstractControl
{
    public function element(): Element
    {
        $input = Html::input();
        $input->setValue($this->getValue());

        return $this->applyAttributesAndClasses($input);
    }

    public function render(Element $control): mixed
    {
        return $control;
    }
}
