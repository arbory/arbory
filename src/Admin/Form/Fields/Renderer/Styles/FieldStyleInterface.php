<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

interface FieldStyleInterface
{
    /**
     * @param RendererInterface $renderer
     * @param StyleOptionsInterface $options
     *
     * @return mixed
     */
    public function render(RendererInterface $renderer, StyleOptionsInterface $options);
}
