<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class EmptyField extends AbstractField
{
    private array $elements = [];

    /**
     * EmptyField constructor.
     *
     * @param string|null $name
     */
    public function __construct(?string $name = '')
    {
        parent::__construct($name);
    }

    public function render(): Element
    {
        return Html::div($this->elements);
    }

    /**
     * @return $this
     */
    public function append(Element $element): self
    {
        $this->elements[] = $element;

        return $this;
    }

    public function isDisabled(): bool
    {
        return true;
    }
}
