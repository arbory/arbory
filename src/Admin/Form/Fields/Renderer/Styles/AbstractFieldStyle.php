<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\FieldRenderOptionsInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\InputRendererInterface;

abstract class AbstractFieldStyle
{
    protected function renderField(FieldInterface $field)
    {
        $content = $field->render();

        if($field instanceof FieldRenderOptionsInterface) {
            if($wrapper = $field->getWrapper()) {
                return $wrapper($content);
            }
        }

        return $content;
    }
}