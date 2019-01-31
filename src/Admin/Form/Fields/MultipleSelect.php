<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelatedOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\AssociatedSetRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class MultipleSelect
 * @package Arbory\Base\Admin\Form\Fields\
 */
class MultipleSelect extends AbstractField
{
    protected $renderer = AssociatedSetRenderer::class;

    use HasRelatedOptions;
}
