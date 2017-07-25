<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class Richtext
 * @package Arbory\Base\Admin\Form\Fields
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
