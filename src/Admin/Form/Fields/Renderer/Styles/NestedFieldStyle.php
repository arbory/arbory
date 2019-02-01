<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class NestedFieldStyle extends AbstractFieldStyle
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
        return Html::section([
            $this->getHeader($field->getLabel()),
            $this->renderField($field),
        ])
           ->addClass('nested')
           ->addClass($field->getFieldClass())
           ->addAttributes([
               'data-name' => $field->getName(),
           ]);
    }
}