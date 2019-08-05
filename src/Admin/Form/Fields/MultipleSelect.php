<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasSelectOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\AssociatedSetRenderer;

/**
 * Class MultipleSelect
 * @package Arbory\Base\Admin\Form\Fields\
 */
class MultipleSelect extends AbstractField
{
    protected $rendererClass = AssociatedSetRenderer::class;

    use HasSelectOptions;
}
