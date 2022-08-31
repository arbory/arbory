<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\SelectControl;
use Arbory\Base\Admin\Form\Fields\ControlFieldInterface;
use Arbory\Base\Html\Elements\Content;

class SelectFieldRenderer extends ControlFieldRenderer
{
    protected ControlFieldInterface $field;

    public function render(): Content
    {
        /**
         * @var SelectControl
         */
        $control = $this->getControl();
        $control = $this->configureControl($control);

        $control->setOptions($this->field->getOptions()->prepend('', '')->all());
        $control->setSelected($this->field->getValue());

        $element = $control->element();

        if ($this->field->isMultiple()) {
            $control->setMultiple(true);

            $name = $control->getName();
            $element->addAttributes([
                'multiple' => '',
                'name' => $name . '[]',
            ]);
        }

        return $control->render($element);
    }
}
