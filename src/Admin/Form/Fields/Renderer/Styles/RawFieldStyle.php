<?php
namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\InputRendererInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class RawFieldStyle implements FieldStyleInterface
{

    public function render(FieldInterface $field)
    {
        $namespacedName = $field->getNameSpacedName();
        $inputName      = $this->getInputName($namespacedName);
        $inputId        = $this->getInputId($inputName);

        $renderer = $field->render();

        if($renderer instanceof InputRendererInterface) {
            $renderer->setAttributes(
                array_replace($renderer->getAttributes(), [
                    'id' => $inputId
                ])
            );

            $content = $renderer->render();
        } else {
            $content = $renderer;
        }

        return $content;
    }

    protected function getInputName($namespacedName)
    {
        return Element::formatName($namespacedName);
    }

    protected function getInputId($name)
    {
        return rtrim(strtr($name, ['[' => '_', ']' => '']), '_');
    }
}