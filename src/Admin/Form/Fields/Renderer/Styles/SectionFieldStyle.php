<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class SectionFieldStyle extends AbstractFieldStyle implements FieldStyleInterface
{
    public function render(RendererInterface $renderer, StyleOptionsInterface $options)
    {
        $field = $renderer->getField();

        $section = Html::div([
            $this->renderField($field),
        ])
            ->addClass('section content-fields')
            ->addClass(implode(' ', $field->getFieldClasses()))
            ->addAttributes([
                'data-name' => $field->getName(),
            ]);

        $section->addAttributes($options->getAttributes());
        $section->addClass(implode(' ', $options->getClasses()));

        if ($field instanceof ControlFieldInterface) {
            if ($field->isDisabled()) {
                $section->addAttributes(['data-disabled' => 1]);
            }

            $section->addAttributes(['data-interactive' => (int) $field->isInteractive()]);
            $section->addAttributes(['data-required' => (int) $field->isRequired()]);
        }

        return $section;
    }
}
