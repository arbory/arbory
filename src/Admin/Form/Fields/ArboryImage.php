<?php

declare(strict_types=1);

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\ImageFieldRenderer;

/**
 * Class ArboryImage.
 */
final class ArboryImage extends ArboryFile
{
    protected $attributes = [
        'type' => 'file',
        'accept' => 'image/*',
    ];

    protected $rendererClass = ImageFieldRenderer::class;
}
