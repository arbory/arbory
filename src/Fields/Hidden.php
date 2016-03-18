<?php

namespace CubeSystems\Leaf\Fields;

class Hidden extends AbstractField
{
    public function render()
    {
        return view( $this->getViewName(), [
            'field' => $this,
        ] );
    }
}
