<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;

class TextareaControl extends AbstractControl
{
    public function element(): Element
    {
        $textarea = Html::textarea(
            $this->getValue()
        );

        return $this->applyAttributesAndClasses($textarea);
    }

    public function render(Element $control): Element
    {
        return $control;
    }
}
