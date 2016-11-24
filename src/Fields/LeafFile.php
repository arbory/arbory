<?php

namespace CubeSystems\Leaf\Fields;

use Illuminate\View\View;

class LeafFile extends AbstractField
{
    /**
     * @return View
     */
    public function render()
    {
        return view( $this->getViewName(), [
            'field' => $this,
        ] );
    }
}