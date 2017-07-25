<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use Arbory\Base\Html\Elements\Element;

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
