<?php


namespace Arbory\Base\Admin\Form\Controls;


use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Html\Elements\Element;

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

    abstract public function element():Element;

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

        if($this->isReadOnly()) {
            $element->addAttributes(
                ['readonly' => '']
            );
        }

        if($this->isDisabled()) {
            $element->addAttributes(
                [ 'disabled' => '']
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
    public function setValue( $value ): InputControlInterface
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

    public function setDisabled( bool $value ): InputControlInterface
    {
        $this->disabled = $value;

        return $this;
    }

    public function isDisabled():bool
    {
        return $this->disabled;
    }

    public function setReadOnly( bool $value ): InputControlInterface
    {
        $this->readOnly = $value;

        return $this;
    }

    public function isReadOnly():bool
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
    public function setName( ?string $name ): InputControlInterface
    {
        $this->name = $name;

        return $this;
    }
}