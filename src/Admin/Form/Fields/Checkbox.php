<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Controls\CheckboxControl as CheckboxControl;
use Arbory\Base\Admin\Form\Fields\Renderer\CheckBoxFieldRenderer;
use Illuminate\Http\Request;

/**
 * Class Checkbox
 * @package Arbory\Base\Admin\Form\Fields
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

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request)
    {
        $value = $request->has($this->getNameSpacedName()) ? $this->checkedValue : $this->uncheckedValue;

        $this->getModel()->setAttribute($this->getName(), $value);
    }

    /**
     * Use custom checked/unchecked values
     *
     * @param mixed $checkedValue
     * @param mixed $uncheckedValue
     *
     * @return $this
     */
    public function values($checkedValue = true, $uncheckedValue = false)
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
