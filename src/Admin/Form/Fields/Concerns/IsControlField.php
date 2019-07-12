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

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     *
     * @return FieldInterface
     */
    public function setDisabled(bool $disabled = false): FieldInterface
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInteractive(): bool
    {
        return $this->interactive;
    }

    /**
     * @param bool $interactive
     *
     * @return FieldInterface
     */
    public function setInteractive(bool $interactive = false): FieldInterface
    {
        $this->interactive = $interactive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return FieldInterface
     */
    public function setRequired(bool $required = false): FieldInterface
    {
        $this->required = $required;

        return $this;
    }
}
