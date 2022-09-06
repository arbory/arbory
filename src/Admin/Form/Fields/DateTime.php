<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Carbon\Carbon;

/**
 * Class DateTime.
 */
class DateTime extends Text
{
    protected array $classes = [
        'text',
        'datetime-picker',
    ];

    protected string $format = 'Y-m-d H:i';

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getValue(): mixed
    {
        $value = parent::getValue();

        if ($value) {
            return Carbon::parse($value)->format($this->getFormat());
        }

        return '';
    }

    public function beforeRender(RendererInterface $renderer)
    {
        if ($this->isDisabled() || ! $this->isInteractive()) {
            $this->removeClasses('datetime-picker');
        }
    }
}
