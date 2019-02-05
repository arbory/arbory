<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class NestedFieldStyle extends AbstractFieldStyle implements FieldStyleInterface
{
    /**
     * @param $label
     *
     * @return Element
     */
    protected function getHeader( $label )
    {
        return Html::header(Html::h1($label));
    }


    public function render(RendererInterface $renderer, StyleOptionsInterface $options)
    {
        $field = $renderer->getField();

        $section = Html::section([
            $this->getHeader($field->getLabel()),
            $this->renderField($field),
        ])
           ->addClass('nested')
           ->addClass(implode(' ', $field->getFieldClasses()))
           ->addAttributes([
               'data-name' => $field->getName(),
           ]);

        $section->addAttributes($options->getAttributes());
        $section->addClass(implode(' ', $options->getClasses()));

        return $section;
    }
}