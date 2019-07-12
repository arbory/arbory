<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\RenderOptionsInterface;
use Arbory\Base\Admin\Form\Controls\InputControlInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class ControlFieldRenderer implements RendererInterface
{
    /**
     * @var FieldInterface|ControlFieldInterface|RenderOptionsInterface
     */
    protected $field;

    /**
     * ControlFieldRenderer constructor.
     *
     * @param ControlFieldInterface $field
     */
    public function __construct(ControlFieldInterface $field)
    {
        $this->field = $field;
    }

    public function render()
    {
        $control = $this->getControl();
        $control = $this->configureControl($control);

        return $control->render($control->element());
    }

    /**
     * @param InputControlInterface $control
     *
     * @return InputControlInterface
     */
    public function configureControl(InputControlInterface $control)
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

    /**
     * @return InputControlInterface
     */
    public function getControl(): InputControlInterface
    {
        return app()->make($this->field->getControl());
    }

    /**
     * @param FieldInterface $field
     *
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
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        return $options;
    }
}
