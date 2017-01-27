<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class Text
 * @package CubeSystems\Leaf\Fields
 */
class Text extends AbstractField
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
        return ( new Renderer\InputFieldRenderer( $this ) )->render();
    }
}
