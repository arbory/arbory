<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer;


use Arbory\Base\Html\Html;

class PasswordFieldRenderer extends InputFieldRenderer
{
    /**
     * @return \Arbory\Base\Html\Elements\Inputs\AbstractInputField
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function render()
    {
        return Html::input()
                   ->setType('password')
                   ->setName($this->field->getNameSpacedName())
                   ->setValue($this->field->getValue())
                   ->addClass('text')
                   ->addAttributes($this->getAttributes());
    }
}