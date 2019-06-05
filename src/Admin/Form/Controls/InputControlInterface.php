<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;

interface InputControlInterface extends RenderOptionsInterface
{
    public function element(): Element;

    public function render(Element $control);

    /**
     * @param $value
     *
     * @return InputControlInterface
     */
    public function setValue($value): self;

    /**
     * @return mixed
     */
    public function getValue();

    public function setDisabled(bool $value): self;

    public function isDisabled(): bool;

    public function setReadOnly(bool $value): self;

    public function isReadOnly(): bool;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     *
     * @return InputControlInterface
     */
    public function setName(?string $name): self;
}
