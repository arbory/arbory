<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Nested;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Illuminate\Contracts\Support\Renderable;

interface ItemInterface extends Renderable, RenderOptionsInterface
{
    public function __invoke(FieldInterface $field, FieldSet $fieldSet, $index = null);
}