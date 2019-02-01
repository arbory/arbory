<?php
namespace Arbory\Base\Admin\Form\Fields\Renderer\Styles;

use Arbory\Base\Admin\Form\Fields\FieldInterface;

class RawFieldStyle extends AbstractFieldStyle
{
    public function render(FieldInterface $field)
    {
        return $this->renderField($field);
    }
}