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
    use HasRelatedOptions;

    /**
     * @return Element|string
     */
    public function render()
    {
        return ( new AssociatedSetRenderer( $this, $this->getOptions() ) )->render();
    }
}
