<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


use Arbory\Base\Admin\Form\Fields\FieldInterface;

class VisibleFieldRenderer implements VisibleFieldRendererInterface
{

    public function render( FieldInterface $field )
    {
        $renderer = new FieldRenderer();

        $renderer->setName($field->getNameSpacedName());

        $renderer->setLabel($field->getLabel());
        $renderer->setValue($field->getValue());

        return $renderer->render()->content();
    }
}