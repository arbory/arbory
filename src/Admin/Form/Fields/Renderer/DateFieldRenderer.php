<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class DateFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class DateFieldRenderer extends InputFieldRenderer
{
    /**
     * @return Element
     */
    protected function getInput()
    {
        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( date( 'Y-m-d H:m', strtotime( $this->field->getValue() ) ) )
            ->addClass( 'text datetime-picker' );
    }
}
