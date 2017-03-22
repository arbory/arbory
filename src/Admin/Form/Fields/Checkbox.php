<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class Checkbox
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Checkbox extends AbstractField
{
    protected $inputValue;

    public function getInputValue()
    {
        return $this->inputValue;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new CheckBoxFieldRenderer( $this ) )->render();
    }
}
