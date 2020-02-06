<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class GenericRenderer implements RendererInterface
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->field->render();
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
