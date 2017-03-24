<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class Text
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Text extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        return ( new Renderer\InputFieldRenderer( $this ) )->render();
    }
}
