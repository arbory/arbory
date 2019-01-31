<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use Arbory\Base\Html\Elements\Element;

class CompactRichtext extends Richtext
{
    /**
     * @var bool 
     */
    protected $isCompact = true;

    /**
     * @var string
     */
    protected $renderer = RichtextFieldRenderer::class;
}
