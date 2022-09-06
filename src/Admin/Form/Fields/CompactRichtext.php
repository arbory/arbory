<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;

class CompactRichtext extends Richtext
{
    /**
     * @var bool
     */
    protected bool $isCompact = true;

    /**
     * @var string
     */
    protected string $rendererClass = RichtextFieldRenderer::class;
}
