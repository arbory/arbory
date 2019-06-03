<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\GroupedSerializableMultiselectRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Element;

class GroupedSerializableMultiselect extends AbstractField
{
    /**
     * @var Collection
     */
    private $valueGroups;

    /**
     * SerializableCheckboxGroup constructor.
     * @param $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->valueGroups = collect();
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave(Request $request)
    {
        $values = [];
        foreach ($this->valueGroups as $groupName => $options) {
            foreach (array_keys($options) as $optionName) {
                $inputName = $this->getNameSpacedName() . '.' . $optionName;
                $value = $request->input($inputName, false);

                if ($value) {
                    $values[$optionName] = true;
                }
            }
        }

        $this->getModel()->{$this->getName()} = $values;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return (new GroupedSerializableMultiselectRenderer($this))->render();
    }

    /**
     * @param string $name
     * @param array $optionGroup
     */
    public function addValueGroup(string $name, array $optionGroup)
    {
        $this->valueGroups->put($name, $optionGroup);
    }

    /**
     * @return Collection
     */
    public function getValueGroups(): Collection
    {
        return $this->valueGroups;
    }

    /**
     * @param string $option
     * @return bool
     */
    public function isChecked(string $option): bool
    {
        $selected = $this->getValue();

        return array_search($option, $selected) !== false;
    }
}