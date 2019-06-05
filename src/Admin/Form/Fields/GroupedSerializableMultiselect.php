<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\GroupedSerializableMultiselectRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Arbory\Base\Html\Elements\Element;

class GroupedSerializableMultiselect extends AbstractField
{
    /**
     * @var Collection
     */
    private $valueGroups;

    /**
     * GroupedSerializableMultiselect constructor.
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
        $this->getModel()->{$this->getName()} = $this->collectValues();
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

    /**
     * @return array
     */
    protected function collectValues(): array
    {
        $values = [];
        foreach ($this->getValueGroups() as $groupName => $options) {
            $values = array_merge($values, $this->getValueGroupValues($options));
        }

        return $values;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getValueGroupValues(array $options): array
    {
        $values = [];
        foreach (array_keys($options) as $optionName) {
            if (Input::get($this->getOptionFieldName($optionName), false)) {
                $values[$optionName] = true;
            }
        }

        return $values;
    }

    /**
     * @param string $optionName
     * @return string
     */
    protected function getOptionFieldName(string $optionName): string
    {
        return $this->getNameSpacedName() . '.' . $optionName;
    }
}