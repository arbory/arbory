<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use Illuminate\Http\Request;

class Boolean extends Checkbox
{
    protected $inputValue = 1;

    public function beforeModelSave( Request $request )
    {
        $value = $request->has( $this->getNameSpacedName() ) ? 1 : 0;

        $this->getModel()->setAttribute( $this->getName(), $value );
    }
}
