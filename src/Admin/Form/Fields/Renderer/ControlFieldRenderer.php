<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\InputControlInterface;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Html\Elements\Element;

class ControlFieldRenderer implements RendererInterface
{
    /**
     * ControlFieldRenderer constructor.
     *
     * @param ControlFieldInterface $field
     */
    public function __construct(protected ControlFieldInterface $field)
    {
    }

    public function render()
    {
        $control = $this->getControl();
        $control = $this->configureControl($control);

        return $control->render($control->element());
    }

    public function configureControl(InputControlInterface $control): InputControlInterface
    {
        $control->addAttributes(
            $this->field->getAttributes()
        );

        if ($this->field->getFieldId()) {
            $control->addAttributes(
                ['id' => $this->field->getFieldId()]
            );
        }

        $control->addClass(
            implode(' ', $this->field->getClasses())
        );

        if ($this->field->getName()) {
            $control->setName(
                Element::formatName($this->field->getNameSpacedName())
            );
        }

        $control->setValue($this->field->getValue());
        $control->setReadOnly(! $this->field->isInteractive());
        $control->setDisabled($this->field->isDisabled());

        return $control;
    }

    public function getControl(): InputControlInterface
    {
        return app()->make($this->field->getControl());
    }

    /**
     * @return self
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * @param StyleOptionsInterface $options
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
