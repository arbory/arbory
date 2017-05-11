<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;
use Illuminate\Http\Request;

/**
 * Class Checkbox
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Checkbox extends AbstractField
{
    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $value = $request->has( $this->getNameSpacedName() ) ?: false;

        $this->getModel()->setAttribute( $this->getName(), $value );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return ( new CheckBoxFieldRenderer( $this ) )->render();
    }
}
