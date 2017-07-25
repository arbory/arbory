<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class TextareaFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class TextareaFieldRenderer extends InputFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'textarea';

    /**
     * @return Element
     */
    protected function getInput()
    {
        $textarea = Html::textarea( $this->field->getValue() );
        $textarea->setName( $this->field->getNameSpacedName() );
        $textarea->addAttributes( [
            'rows' => 5,
            'cols' => 50,
        ] );

        return $textarea;
    }
}
