<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class Richtext
 * @package Arbory\Base\Admin\Form\Fields
 */
class Richtext extends Textarea
{
    /**
     * @var
     */
    protected $attachmentsUploadUrl;

    /**
     * @var bool
     */
    protected $isCompact = false;

    /**
     * @var string
     */
    protected $rendererClass = RichtextFieldRenderer::class;

    /**
     * @return mixed
     */
    public function getAttachmentsUploadUrl()
    {
        return $this->attachmentsUploadUrl;
    }

    /**
     * @param mixed $attachmentsUploadUrl
     *
     * @return Richtext
     */
    public function setAttachmentsUploadUrl( $attachmentsUploadUrl ): Richtext
    {
        $this->attachmentsUploadUrl = $attachmentsUploadUrl;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCompact(): bool
    {
        return $this->isCompact;
    }

    /**
     * @param bool $isCompact
     *
     * @return Richtext
     */
    public function setCompact( bool $isCompact ): Richtext
    {
        $this->isCompact = $isCompact;

        return $this;
    }
}
