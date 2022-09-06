<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Html\Elements\Element;

abstract class AbstractControl implements InputControlInterface
{
    use HasRenderOptions;

    protected mixed $value;

    protected bool $readOnly = false;
    protected bool $disabled = false;

    /**
     * @var string|null
     */
    protected ?string $name;

    abstract public function element(): Element;

    abstract public function render(Element $control): mixed;

    public function applyAttributesAndClasses(Element $element): Element
    {
        $element->addAttributes($this->getAttributes());
        $element->addClass(
            implode(' ', $this->getClasses())
        );

        if ($this->isReadOnly()) {
            $element->addAttributes(
                ['readonly' => '']
            );
        }

        if ($this->isDisabled()) {
            $element->addAttributes(
                ['disabled' => '']
            );
        }

        $element->attributes()->put('name', $this->getName());

        return $element;
    }

    public function setValue(mixed $value): InputControlInterface
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setDisabled(bool $value): InputControlInterface
    {
        $this->disabled = $value;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setReadOnly(bool $value): InputControlInterface
    {
        $this->readOnly = $value;

        return $this;
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): InputControlInterface
    {
        $this->name = $name;

        $this->addAttributes(['id' => $this->getInputId($name)]);

        return $this;
    }

    /**
     * Converts dot donation name to a input name.
     */
    public function getInputName(string $namespacedName): string
    {
        return Element::formatName($namespacedName);
    }

    /**
     * Creates Input ID from input name.
     */
    public function getInputId(string $inputName): string
    {
        return rtrim(strtr($inputName, ['[' => '_', ']' => '']), '_');
    }

    public function getInputIdFromNamespace(string $namespacedName): string
    {
        return $this->getInputId(
            $this->getInputName($namespacedName)
        );
    }
}
