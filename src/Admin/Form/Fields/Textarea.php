<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\TextareaFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class Textarea
 * @package CubeSystems\Leaf\Admin\Form\Fields
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
