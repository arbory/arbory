<?php

namespace CubeSystems\Leaf\Fields;

class RemoveRelationItem extends AbstractField
{
    public function render()
    {
        return view( $this->getViewName(), [
            'field' => $this,
        ] );
    }
}


