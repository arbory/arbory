<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\TextareaFieldRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class Textarea
 * @package Arbory\Base\Admin\Form\Fields
 */
class Textarea extends AbstractField
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new TextareaFieldRenderer( $this ) )->render();
    }
}
