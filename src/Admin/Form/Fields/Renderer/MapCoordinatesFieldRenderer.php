<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\MapCoordinates;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class MapCoordinatesFieldRenderer implements RendererInterface
{
    public function __construct(protected MapCoordinates $field)
    {
    }

    /**
     * @return Element
     */
    public function render()
    {
        $value = $this->field->getValue();
        $body = Html::div();

        $body->append(Html::div()->addClass('canvas'));

        $body->append(
            $this->field->getNestedFieldSet($this->field->getModel())->render()
        );

        return $body->addClass('body');
    }

    /**
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $this;
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
