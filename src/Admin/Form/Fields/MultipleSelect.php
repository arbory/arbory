<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Concerns\HasRelatedOptions;
use CubeSystems\Leaf\Admin\Form\Fields\Renderer\AssociatedSetRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class MultipleSelect
 * @package CubeSystems\Leaf\Admin\Form\Fields\
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
