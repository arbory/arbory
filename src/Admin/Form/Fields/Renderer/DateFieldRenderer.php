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
        $value = $this->field->getValue();

        if( !$value )
        {
            $value = date( 'Y-m-d H:m' );
        }

        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( date( 'Y-m-d H:m', strtotime( $value ) ) )
            ->addClass( 'text datetime-picker' );
    }
}
