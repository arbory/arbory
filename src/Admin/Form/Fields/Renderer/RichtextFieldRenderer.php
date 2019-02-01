<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;

/**
 * Class RichtextFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class RichtextFieldRenderer extends ControlFieldRenderer
{
    /**
     * @return Element
     */
    public function render()
    {
        $textarea = parent::render();
        $textarea->addClass( 'richtext type-richText' );
        $textarea->addAttributes( [
            'data-attachment-upload-url' => $this->field->getAttachmentsUploadUrl(),
        ]);


        if ( $this->field->isCompact() )
        {
            $textarea->addClass( 'compact' );
        }
        else
        {
            $textarea->addClass( 'full' );
        }

        return $textarea;
    }
}
