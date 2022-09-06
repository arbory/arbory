<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RichtextFieldRenderer;
use Illuminate\Http\Request;

/**
 * Class Richtext.
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
    protected bool $isCompact = false;

    /**
     * @var string
     */
    protected string $rendererClass = RichtextFieldRenderer::class;

    /**
     * @var null|array
     */
    protected $allowedTags = null;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->allowedTags = config('arbory.fields.richtext.allowed_tags');
    }

    public function beforeModelSave(Request $request): void
    {
        if ($this->isDisabled()) {
            return;
        }

        $value = $request->has($this->getNameSpacedName())
            ? $request->input($this->getNameSpacedName())
            : null;

        if (is_string($value) && is_array($this->allowedTags)) {
            $value = strip_tags($value, $this->allowedTags);
        }

        $this->getModel()->setAttribute($this->getName(), $value);
    }

    /**
     * @return mixed
     */
    public function getAttachmentsUploadUrl()
    {
        return $this->attachmentsUploadUrl;
    }

    public function setAttachmentsUploadUrl(mixed $attachmentsUploadUrl): self
    {
        $this->attachmentsUploadUrl = $attachmentsUploadUrl;

        return $this;
    }

    public function isCompact(): bool
    {
        return $this->isCompact;
    }

    public function setCompact(bool $isCompact): self
    {
        $this->isCompact = $isCompact;

        return $this;
    }
}
