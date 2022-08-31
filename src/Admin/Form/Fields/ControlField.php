<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\InputControl;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\ControlFieldRenderer;
use Arbory\Base\Html\Elements\Element;

class ControlField extends AbstractField implements RenderOptionsInterface
{
    use HasRenderOptions;

    protected $control = InputControl::class;
    protected $rendererClass = ControlFieldRenderer::class;

    /**
     * @return string|null
     */
    public function getFieldId()
    {
        return $this->getName() ? $this->getInputIdFromNamespace(
            $this->getNameSpacedName()
        ) : null;
    }

    /**
     * Converts dot donation name to a input name.
     *
     * @param $namespacedName
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
     * @return string
     */
    public function getInputId($inputName)
    {
        return rtrim(strtr($inputName, ['[' => '_', ']' => '']), '_');
    }

    /**
     * @param $namespacedName
     * @return string
     */
    public function getInputIdFromNamespace($namespacedName)
    {
        return $this->getInputId(
            $this->getInputName($namespacedName)
        );
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        return $this->control;
    }

    public function setControl(mixed $control): self
    {
        $this->control = $control;

        return $this;
    }
}
