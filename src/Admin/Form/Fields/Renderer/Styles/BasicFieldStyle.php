<?php
namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Html;

class BasicFieldStyle extends AbstractFieldStyle
{
    public function render(FieldInterface $field)
    {
        $template = Html::div()->addClass('field');
        $template->addClass($field->getFieldClass());

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