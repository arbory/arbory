<?php

namespace Arbory\Base\Admin\Form\Fields;

use Carbon\Carbon;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;

/**
 * Class DateTime.
 */
class DateTime extends Text
{
    protected $classes = [
        'text',
        'datetime-picker',
    ];

    protected $format = 'Y-m-d H:i';

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return DateTime
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getValue()
    {
        $value = parent::getValue();

        if ($value) {
            return Carbon::parse($value)->format($this->getFormat());
        }
    }

    public function beforeRender(RendererInterface $renderer)
    {
        if ($this->isDisabled() || ! $this->isInteractive()) {
            $this->removeClasses('datetime-picker');
        }
    }
}
