<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * Class TextareaFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class TextareaFieldRenderer extends InputFieldRenderer implements InputRendererInterface
{
    /**
     * @var string
     */
    protected $type = 'textarea';


    /**
     * @return Element
     */
    public function render()
    {
        $textarea = Html::textarea( $this->field->getValue() );
        $textarea->setName( $this->field->getNameSpacedName() );
        $textarea->addAttributes( array_merge([
            'rows' => 5,
            'cols' => 50,
        ], $this->attributes) );

        return $textarea;
    }
}
