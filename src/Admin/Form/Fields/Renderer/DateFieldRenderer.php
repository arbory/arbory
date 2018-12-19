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

        if ( !$value && !$this->field->isNullAllowed() ) {
            $value = date( 'Y-m-d H:i' );
        }

        $input = Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->addClass( 'text datetime-picker' );

        if ( $value ) {
            $input->setValue( date( 'Y-m-d H:i', strtotime( $value ) ) );
        }

        return $input;
    }
}
