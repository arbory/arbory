<?php

namespace CubeSystems\Leaf\Fields;

use CubeSystems\Leaf\Html\Html;

class Gravatar extends AbstractField
{
    public function __toString()
    {
        return (string) Html::image()->addAttributes( [
            'src' => '//www.gravatar.com/avatar/' . md5( $this->getValue() ) . '?d=retro',
            'width' => 32,
            'alt' => $this->getValue(),
        ] );
    }

    public function render()
    {
        return '';
    }
}
