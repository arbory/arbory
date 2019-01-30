<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Illuminate\Contracts\Support\Renderable;

interface VisibleFieldRendererInterface
{
    /**
     * @param FieldInterface $field
     *
     * @return string|Renderable
     */
    public function render(FieldInterface $field);
}