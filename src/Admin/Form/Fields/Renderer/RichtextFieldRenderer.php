<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

/**
 * Class RichtextFieldRenderer.
 */
class RichtextFieldRenderer extends ControlFieldRenderer
{
    /**
     * @param RenderOptionsInterface $options
     *
     * @return RenderOptionsInterface
     */
    public function configureOptions(RenderOptionsInterface $options)
    {
        return $options;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $textarea = parent::render();
        $textarea->addClass('richtext type-richText');
        $textarea->addAttributes([
            'data-attachment-upload-url' => $this->field->getAttachmentsUploadUrl(),
        ]);

        if ($this->field->isCompact()) {
            $textarea->addClass('compact');
        } else {
            $textarea->addClass('full');
        }

        return $textarea;
    }
}
