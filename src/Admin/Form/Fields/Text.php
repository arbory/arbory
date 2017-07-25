<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;

/**
 * Class Text
 * @package Arbory\Base\Admin\Form\Fields
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
