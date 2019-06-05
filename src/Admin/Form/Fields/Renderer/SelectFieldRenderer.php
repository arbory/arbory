<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\SelectControl;

class SelectFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    public function render()
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
                'name' => $name.'[]',
            ]);
        }

        return $control->render($element);
    }
}
