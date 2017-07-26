<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class DateFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
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
            $value = date( 'Y-m-d H:i' );
        }

        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( date( 'Y-m-d H:i', strtotime( $value ) ) )
            ->addClass( 'text datetime-picker' );
    }
}
