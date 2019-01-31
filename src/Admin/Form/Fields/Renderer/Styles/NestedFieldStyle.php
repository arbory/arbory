<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\InputRendererInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class NestedFieldStyle implements FieldStyleInterface
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


    public function render( FieldInterface $field )
    {
        $namespacedName = $field->getNameSpacedName();
        $inputName      = $this->getInputName($namespacedName);
        $inputId        = $this->getInputId($inputName);

        $renderer = $field->render();

        if ( $renderer instanceof InputRendererInterface ) {
            $renderer->setAttributes(
                array_replace($renderer->getAttributes(), [
                    'id' => $inputId,
                ])
            );

            $content = $renderer->render();
        } else {
            $content = $renderer;
        }


        return Html::section([
            $this->getHeader($field->getLabel()),
            $content,
        ])
           ->addClass('nested')
           ->addAttributes([
               'data-name' => $field->getName(),
           ]);
    }

    protected function getInputName( $namespacedName )
    {
        return Element::formatName($namespacedName);
    }

    protected function getInputId( $name )
    {
        return rtrim(strtr($name, [ '[' => '_', ']' => '' ]), '_');
    }
}