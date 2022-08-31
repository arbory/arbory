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

    /**
     * @return Element
     */
    public function render(Element $control)
    {
        return $control;
    }
}
