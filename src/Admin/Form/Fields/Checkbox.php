<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Http\Request;

/**
 * Class Checkbox
 * @package Arbory\Base\Admin\Form\Fields
 */
class Checkbox extends AbstractField
{
    protected $renderer = CheckBoxFieldRenderer::class;

    protected $style = 'basic';

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $value = $request->has( $this->getNameSpacedName() ) ?: false;

        $this->getModel()->setAttribute( $this->getName(), $value );
    }
}
