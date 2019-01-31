<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;


use Arbory\Base\Admin\Form\Fields\FieldInterface;

interface FieldStyleInterface
{
    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function render(FieldInterface $field);
}