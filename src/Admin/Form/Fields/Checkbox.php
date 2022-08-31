<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;
use Arbory\Base\Admin\Form\Controls\CheckboxControl;

/**
 * Class Checkbox.
 */
class Checkbox extends ControlField
{
    protected $rendererClass = CheckBoxFieldRenderer::class;

    protected $control = CheckboxControl::class;

    protected $style = 'basic';

    /**
     * @var mixed
     */
    protected $checkedValue = true;

    /**
     * @var mixed
     */
    protected $uncheckedValue = false;

    public function beforeModelSave(Request $request)
    {
        $value = $request->has($this->getNameSpacedName()) ? $this->checkedValue : $this->uncheckedValue;

        $this->getModel()->setAttribute($this->getName(), $value);
    }

    /**
     * Use custom checked/unchecked values.
     *
     * @return $this
     */
    public function values(mixed $checkedValue = true, mixed $uncheckedValue = false)
    {
        $this->checkedValue = $checkedValue;
        $this->uncheckedValue = $uncheckedValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckedValue()
    {
        return $this->checkedValue;
    }

    /**
     * @return mixed
     */
    public function getUncheckedValue()
    {
        return $this->uncheckedValue;
    }
}
