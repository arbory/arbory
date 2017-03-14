<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class TextareaFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class TextareaFieldRenderer extends BaseRenderer
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
