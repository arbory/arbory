<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

abstract class AbstractFieldStyle
{
    protected function renderField(FieldInterface $field)
    {
        $content = $field->render();

        if ($field instanceof RenderOptionsInterface) {
            if ($wrapper = $field->getWrapper()) {
                return $wrapper($content);
            }
        }

        return $content;
    }
}
