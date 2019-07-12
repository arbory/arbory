<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\TextareaControl;

/**
 * Class Textarea.
 */
class Textarea extends ControlField
{
    protected $control = TextareaControl::class;

    protected $attributes = [
        'rows' => 5,
        'cols' => 50,
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}
