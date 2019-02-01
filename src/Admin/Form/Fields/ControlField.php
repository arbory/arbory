<?php


namespace Arbory\Base\Admin\Form\Fields;


use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\Concerns\IsControlField;
use Arbory\Base\Admin\Form\Fields\Renderer\ControlFieldRenderer;
use Arbory\Base\Html\Elements\Element;

class ControlField extends AbstractField implements ControlFieldInterface, FieldRenderOptionsInterface

{
    use IsControlField;
    use HasRenderOptions;

    const ELEMENT_TYPE_INPUT = 'input';
    const ELEMENT_TYPE_SELECT = 'select';
    const ELEMENT_TYPE_TEXTAREA = 'textarea';

    protected $elementType = self::ELEMENT_TYPE_INPUT;

    protected $renderer = ControlFieldRenderer::class;

    /**
     * @return string
     */
    public function getElementType(): string
    {
        return $this->elementType;
    }

    /**
     * @param string $elementType
     */
    public function setElementType( string $elementType ): void
    {
        $this->elementType = $elementType;
    }

    public function getFieldId()
    {
        return $this->getInputIdFromNamespace(
            $this->getNameSpacedName()
        );
    }


    /**
     * Converts dot donation name to a input name
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
     * Creates Input ID from input name
     *
     * @param $inputName
     *
     * @return string
     */
    public function getInputId($inputName)
    {
        return rtrim(strtr($inputName, [ '[' => '_', ']' => '']), '_');
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