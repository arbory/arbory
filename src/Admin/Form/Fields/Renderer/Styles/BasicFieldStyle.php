<?php
namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\InputRendererInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class BasicFieldStyle implements FieldStyleInterface
{

    public function render(FieldInterface $field)
    {
        $namespacedName = $field->getNameSpacedName();
        $inputName      = $this->getInputName($namespacedName);
        $inputId        = $this->getInputId($inputName);
        $type           = str_slug(class_basename(get_class($field)));

        $template = Html::div()->addClass('field');
        $template->addClass('type-' . $type);

        if ($name = $field->getName()) {
            $template->addAttributes(
                [
                    'data-name' => $name
                ]
            );
        }

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

        $template->append($content);


        return $template;
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