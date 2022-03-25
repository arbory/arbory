<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class EmptyField extends AbstractField
{
    /**
     * @var array
     */
    private $elements = [];

    /**
     * EmptyField constructor.
     *
     * @param  string|null  $name
     */
    public function __construct(?string $name = '')
    {
        parent::__construct($name);
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div($this->elements);
    }

    /**
     * @param  Element  $element
     * @return $this
     */
    public function append(Element $element): self
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return true;
    }
}
