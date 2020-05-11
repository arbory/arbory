<?php

namespace Arbory\Base\Admin\Form\Fields;

interface ControlFieldInterface
{
    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     *
     * @return mixed
     */
    public function setRequired(bool $required = false): FieldInterface;

    /**
     * @return bool
     */
    public function isDisabled(): bool;

    /**
     * Disable any interaction and does not send any input back to the server.
     *
     * @param bool $disabled
     *
     * @return FieldInterface
     */
    public function setDisabled(bool $disabled = false): FieldInterface;

    /**
     * @return bool
     */
    public function isInteractive(): bool;

    /**
     * Disables any interaction but does not control if the field is changed.
     *
     * @param bool $value
     *
     * @return FieldInterface
     */
    public function setInteractive(bool $value = false): FieldInterface;
}
