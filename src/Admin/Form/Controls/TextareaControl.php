<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;

class TextareaControl extends AbstractControl
{
    /**
     * @return Element
     */
    public function element(): Element
    {
        $textarea = Html::textarea(
            $this->getValue()
        );

        $textarea = $this->applyAttributesAndClasses($textarea);

        return $textarea;
    }

    /**
     * @return Element
     */
    public function render(Element $control)
    {
        return $control;
    }
}
