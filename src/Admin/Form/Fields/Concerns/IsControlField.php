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
    protected $readOnly = false;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @return bool
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     *
     * @return FieldInterface
     */
    public function setDisabled( bool $disabled = false ): FieldInterface
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * @param bool $readOnly
     *
     * @return FieldInterface
     */
    public function setReadOnly( bool $readOnly = false ): FieldInterface
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return FieldInterface
     */
    public function setRequired( bool $required = false ): FieldInterface
    {
        $this->required = $required;

        return $this;
    }
}