<?php


namespace Arbory\Base\Admin\Form\Fields;


interface ControlFieldInterface
{
    /**
     * @return bool
     */
    public function getRequired(): bool;

    /**
     * @param bool $required
     *
     * @return mixed
     */
    public function setRequired( bool $required = false ): FieldInterface;

    /**
     * @return bool
     */
    public function getDisabled(): bool;

    /**
     * @param bool $disabled
     *
     * @return FieldInterface
     */
    public function setDisabled( bool $disabled = false ): FieldInterface;

    /**
     * @return bool
     */
    public function getReadOnly(): bool;

    /**
     * @param bool $readOnly
     *
     * @return FieldInterface
     */
    public function setReadOnly( bool $readOnly = false ): FieldInterface;
}