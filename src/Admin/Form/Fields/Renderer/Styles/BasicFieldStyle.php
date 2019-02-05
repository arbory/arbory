<?php
namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\RendererInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Html\Html;

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
                    'data-name' => $name
                ]
            );
        }

        $template->append($this->renderField($field));
        
        return $template;
    }
}