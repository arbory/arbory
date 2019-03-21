<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Illuminate\Contracts\Support\Renderable;

interface RendererInterface extends Renderable
{
    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface;

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface;

    /**
     * Configure the style before rendering the field
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface;
}
