<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class Richtext
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Richtext extends AbstractField
{
    /**
     * @return Element
     */
    public function render()
    {
        return ( new RichtextFieldRenderer( $this ) )
            ->setAttachmentsUploadUrl( null )
            ->render();
    }
}
