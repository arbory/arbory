<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class InputFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class InputFieldRenderer extends BaseRenderer
{
    /**
     * @return Element
     */
    protected function getInput()
    {
        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( $this->field->getValue() )
            ->addClass( 'text' );
    }
}
