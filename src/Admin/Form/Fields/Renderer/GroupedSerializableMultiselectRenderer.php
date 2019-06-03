<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\GroupedSerializableMultiselect;
use Arbory\Base\Html\Elements\Inputs\AbstractInputField;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Support\Collection;

class GroupedSerializableMultiselectRenderer
{
    /**
     * @var GroupedSerializableMultiselect
     */
    private $multiselectField;

    /**
     * SerializableCheckboxGroupRenderer constructor.
     * @param GroupedSerializableMultiselect $multiselectField
     */
    public function __construct(GroupedSerializableMultiselect $multiselectField)
    {
        $this->multiselectField = $multiselectField;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $content = [];
        foreach ($this->multiselectField->getValueGroups() as $groupName => $fields) {
            $content[] = $this->getGroupContent($groupName, $fields);
        }

        $label = Html::div($this->multiselectField->getName())->addClass('label-wrap');

        return Html::div([$label, $content])->addClass('field type-serializable-checkbox-group');
    }

    /**
     * @param string $groupName
     * @param array $fields
     * @return Element
     */
    private function getGroupContent(string $groupName, array $fields)
    {
        $checkboxes = [];
        foreach ($fields as $optionValue => $label) {
            $checkboxes[$optionValue] = $this->getValueCheckbox($optionValue, $label);
        }

        $header = Html::h2($groupName)->addClass('label-wrap');
        $group = Html::div([
            $header,
            $checkboxes
        ])->addClass('checkbox-group');

        return $group;
    }

    /**
     * @param string $optionValue
     * @param string $label
     * @return AbstractInputField
     */
    private function getValueCheckbox(string $optionValue, string $label): AbstractInputField
    {
        $optionName = $this->namespacedOptionName($optionValue);
        $input = (new Input)->setName($optionName)->getLabel($label);
        $checkbox = Html::checkbox($input)
            ->addClass('serializable-checkbox')
            ->setName($optionName);

        if ($this->multiselectField->isChecked($optionValue)) {
            $checkbox->select();
        }

        return $checkbox;
    }

    /**
     * @param string $optionValue
     * @return string
     */
    private function namespacedOptionName(string $optionValue): string
    {
        return $this->multiselectField->getNameSpacedName() . '.' . $optionValue;
    }
}