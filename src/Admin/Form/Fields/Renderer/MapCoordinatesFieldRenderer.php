<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\MapCoordinates;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class MapCoordinatesFieldRenderer implements RendererInterface
{
    /**
     * @var MapCoordinates
     */
    protected $field;

    /**
     * @param MapCoordinates $field
     */
    public function __construct(MapCoordinates $field)
    {
        $this->field = $field;
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
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
