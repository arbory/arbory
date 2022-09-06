<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;

/**
 * Class Hidden.
 */
class Hidden extends ControlField
{
    protected string $style = 'raw';

    protected array $attributes = [
        'type' => 'hidden',
    ];

    public function beforeRender(RendererInterface $renderer)
    {
        $this->addAttributes([
            'data-name' => $this->getName(),
        ]);
    }
}
