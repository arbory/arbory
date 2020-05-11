<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;

abstract class AbstractControl implements InputControlInterface
{
    use HasRenderOptions;

    protected $value;

    protected $readOnly = false;
    protected $disabled = false;

    /**
     * @var string|null
     */
    protected $name;

    abstract public function element(): Element;

    /**
     * @return Element
     */
    abstract public function render(Element $control);

    /**
     * @param Element $element
     *
     * @return Element
     */
    public function applyAttributesAndClasses(Element $element)
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

    /**
     * @param $value
     *
     * @return InputControlInterface
     */
    public function setValue($value): InputControlInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
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

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return InputControlInterface
     */
    public function setName(?string $name): InputControlInterface
    {
        $this->name = $name;

        $this->addAttributes(['id' => $this->getInputId($name)]);

        return $this;
    }

    /**
     * Converts dot donation name to a input name.
     *
     * @param $namespacedName
     *
     * @return string
     */
    public function getInputName($namespacedName)
    {
        return Element::formatName($namespacedName);
    }

    /**
     * Creates Input ID from input name.
     *
     * @param $inputName
     *
     * @return string
     */
    public function getInputId($inputName)
    {
        return rtrim(strtr($inputName, ['[' => '_', ']' => '']), '_');
    }

    /**
     * @param $namespacedName
     *
     * @return string
     */
    public function getInputIdFromNamespace($namespacedName)
    {
        return $this->getInputId(
            $this->getInputName($namespacedName)
        );
    }
}
