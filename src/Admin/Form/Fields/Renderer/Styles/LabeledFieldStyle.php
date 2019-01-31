<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;


use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\InputRendererInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class LabeledFieldStyle implements FieldStyleInterface
{

    public function render(FieldInterface $field)
    {
        $namespacedName = $field->getNameSpacedName();
        $inputName      = $this->getInputName($namespacedName);
        $inputId        = $this->getInputId($inputName);
        $type           = str_slug(class_basename(get_class($field)));
        $value          = $field->getValue();

        $template = Html::div()->addClass('field');
        $template->addClass('type-' . $type);

        if ($name = $field->getName()) {
            $template->addAttributes(
                [
                    'data-name' => $name
                ]
            );
        }

        $template->append($this->buildLabel($field, $inputId));

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

        $template->append(Html::div($content)->addClass('value'));


        return $template;
    }

    protected function buildLabel(FieldInterface $field, $inputId)
    {
        $element = Html::div()->addClass('label-wrap');

        $label = Html::label($field->getLabel())
                     ->addAttributes(['for' => $inputId]);

        $element->append($label);

        if ($field->getRequired()) {
            $label->prepend(Html::span('*')->addClass('required-form-field'));
        }

        if ($info = $field->getInfoBlock()) {
            $element->append(
                Html::abbr(' ?')->addAttributes(['title' => $info])
            );
        }

        return $element;
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