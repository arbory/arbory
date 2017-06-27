<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use CubeSystems\Leaf\Html\Elements\Element;

class CompactRichtext extends Richtext
{
    /**
     * @return Element
     */
    public function render()
    {
        return ( new RichtextFieldRenderer( $this ) )
            ->setCompact( true )
            ->setAttachmentsUploadUrl( null )
            ->render();
    }
}