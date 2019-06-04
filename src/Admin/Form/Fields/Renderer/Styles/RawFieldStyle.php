<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class RawFieldStyle extends AbstractFieldStyle
{
    public function render(RendererInterface $renderer, StyleOptionsInterface $options)
    {
        return $this->renderField($renderer->getField());
    }
}
