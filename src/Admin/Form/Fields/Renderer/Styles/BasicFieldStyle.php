<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class BasicFieldStyle extends AbstractFieldStyle implements FieldStyleInterface
{
    public function render(RendererInterface $renderer, StyleOptionsInterface $options)
    {
        $field = $renderer->getField();

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

        $template->append($this->renderField($field));

        if ($info = $field->getTooltip()) {
            $template->append(
                Html::abbr(' ?')->addAttributes(['title' => $info])
            );
        }

        return $template;
    }
}
