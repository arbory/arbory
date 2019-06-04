<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

interface RendererInterface extends Renderable
{
    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField(FieldInterface $field): self;

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface;

    /**
     * Configure the style before rendering the field.
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface;
}
