<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\TextareaFieldRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class Textarea
 * @package Arbory\Base\Admin\Form\Fields
 */
class Textarea extends ControlField
{
    protected $elementType = self::ELEMENT_TYPE_TEXTAREA;

    protected $attributes = [
        'rows' => 5,
        'cols' => 50
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}
