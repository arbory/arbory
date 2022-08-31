<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\TextareaControl;

/**
 * Class Textarea.
 */
class Textarea extends ControlField
{
    protected $control = TextareaControl::class;

    protected array $attributes = [
        'rows' => 5,
        'cols' => 50,
    ];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
