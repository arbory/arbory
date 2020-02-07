<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;

/**
 * Class CheckBoxFieldRenderer.
 */
class CheckBoxFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Checkbox
     */
    protected $field;

    /**
     * @return Element
     */
    public function render()
    {
        $control = $this->getControl();
        $control = $this->configureControl($control);

        $element = $control->element();

        $element->setValue($this->field->getCheckedValue());

        $control->setChecked(
            $this->field->getValue() == true
        );

        return Html::div([
            Html::label($this->field->getLabel())->addAttributes(['for' => $this->field->getFieldId()])
                ->append($control->render($element))
                ->append( Html::span()
                ),
        ])->addClass('value');
    }
}
