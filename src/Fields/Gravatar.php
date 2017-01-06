<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class Gravatar extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        return Html::image()->addAttributes( [
            'src' => '//www.gravatar.com/avatar/' . md5( $this->getValue() ) . '?d=retro',
            'width' => 32,
            'alt' => $this->getValue(),
        ] );
    }
}
