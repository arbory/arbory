<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Controls\Select as SelectControl;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Widgets\Select;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class SelectFieldRenderer extends ControlFieldRenderer
{
    /**
     * @var \Arbory\Base\Admin\Form\Fields\Select
     */
    protected $field;

    public function render()
    {
        /**
         * @var $control SelectControl
         */
        $control = $this->getControl();
        $control = $this->configureControl($control);

        $control->setOptions($this->field->getOptions()->all());
        $control->setSelected($this->field->getValue());

        $element = $control->element();

        if($this->field->isMultiple()) {
            $control->setMultiple(true);

            $name =  $control->getName();
            $element->addAttributes([
                'multiple' => '',
                'name' => $name . '[]'
            ]);
        }

        return $control->render($element);
    }
}
