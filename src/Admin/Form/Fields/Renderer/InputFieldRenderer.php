<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\AbstractInputField;
use Arbory\Base\Html\Html;

/**
 * Class InputFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class InputFieldRenderer implements InputRendererInterface
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * InputFieldRenderer constructor.
     *
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @return AbstractInputField
     */
    public function render()
    {
        return Html::input()
            ->setName( $this->field->getNameSpacedName() )
            ->setValue( $this->field->getValue() )
            ->addClass( 'text' )
            ->addAttributes($this->getAttributes());
    }

    /**
     * @param array $attributes
     *
     * @return InputRendererInterface
     */
    public function setAttributes($attributes = []): InputRendererInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
