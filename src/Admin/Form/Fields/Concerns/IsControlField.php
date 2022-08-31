<?php

namespace Arbory\Base\Admin\Form\Fields\Concerns;

use Arbory\Base\Admin\Form\Fields\FieldInterface;

trait IsControlField
{
    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var bool
     */
    protected $interactive = true;

    /**
     * @var bool
     */
    protected $required = false;

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled = false): FieldInterface
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function isInteractive(): bool
    {
        return $this->interactive;
    }

    public function setInteractive(bool $interactive = false): FieldInterface
    {
        $this->interactive = $interactive;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required = false): FieldInterface
    {
        $this->required = $required;

        return $this;
    }
}
