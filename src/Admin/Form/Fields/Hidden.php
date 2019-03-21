<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;

/**
 * Class Hidden
 * @package Arbory\Base\Admin\Form\Fields
 */
class Hidden extends ControlField
{
    protected $style = 'raw';

    protected $attributes = [
        'type' => 'hidden'
    ];

    public function beforeRender(RendererInterface $renderer)
    {
        $this->addAttributes([
            'data-name' => $this->getName()
        ]);
    }
}
