<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class LabeledFieldStyle extends AbstractFieldStyle implements FieldStyleInterface
{
    public function render(RendererInterface $renderer, StyleOptionsInterface $options)
    {
        $field = $renderer->getField();

        $inputId = $field->getFieldId();
        $template = Html::div()->addClass('field');
        $template->addClass(implode(' ', $field->getFieldClasses()));

        $template->addAttributes($options->getAttributes());
        $template->addClass(implode(' ', $options->getClasses()));

        if ($name = $field->getName()) {
            $template->addAttributes(
                [
                    'data-name' => $name,
                ]
            );
        }

        if ($field instanceof ControlFieldInterface) {
            if ($field->isDisabled()) {
                $template->addAttributes(['data-disabled' => 1]);
            }

            $template->addAttributes(['data-interactive' => (int) $field->isInteractive()]);
            $template->addAttributes(['data-required' => (int) $field->isRequired()]);
        }

        $template->append($this->buildLabel($field, $inputId));
        $template->append(Html::div($this->renderField($field))->addClass('value'));

        return $template;
    }

    protected function buildLabel(FieldInterface $field, $inputId)
    {
        $element = Html::div()->addClass('label-wrap');

        $label = Html::label($field->getLabel())
            ->addAttributes(['for' => $inputId]);

        $element->append($label);

        if ($field instanceof ControlFieldInterface && $field->isRequired()) {
            $label->prepend(Html::span('*')->addClass('required'));
        }

        if ($info = $field->getTooltip()) {
            $element->append(
                Html::abbr(' ?')->addAttributes(['title' => $info])
            );
        }

        return $element;
    }
}
