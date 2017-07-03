<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Html\Elements\Element;

/**
 * Class RichtextFieldRenderer
 * @package CubeSystems\Leaf\Admin\Form\Fields\Renderer
 */
class RichtextFieldRenderer extends TextareaFieldRenderer
{
    /**
     * @var string
     */
    protected $type = 'richtext';

    /**
     * @var
     */
    protected $attachmentsUploadUrl;

    /**
     * @var bool
     */
    protected $compact = false;

    /**
     * @param $url
     * @return RichtextFieldRenderer
     */
    public function setAttachmentsUploadUrl( $url )
    {
        $this->attachmentsUploadUrl = $url;

        return $this;
    }

    /**
     * @return Element
     */
    protected function getInput()
    {
        $textarea = parent::getInput();
        $textarea->addClass( 'richtext type-richText' );
        $textarea->addAttributes( [
            'data-attachment-upload-url' => $this->attachmentsUploadUrl,
        ]);


        if ( $this->isCompact() )
        {
            $textarea->addClass( 'compact' );
        }
        else
        {
            $textarea->addClass( 'full' );
        }

        return $textarea;
    }

    /**
     * @return bool
     */
    public function isCompact(): bool
    {
        return $this->compact;
    }

    /**
     * @param bool $compact
     * @return self
     */
    public function setCompact( bool $compact )
    {
        $this->compact = $compact;

        return $this;
    }
}
