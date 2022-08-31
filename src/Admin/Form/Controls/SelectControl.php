<?php

namespace Arbory\Base\Admin\Form\Controls;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Html\Elements\Inputs\Input;
use Illuminate\Support\Arr;

class SelectControl extends AbstractControl
{
    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $selected = [];

    public function element(): Element
    {
        $select = Html::select($this->buildOptions());

        $select = $this->applyAttributesAndClasses($select);

        if ($this->isReadOnly()) {
            $select->addAttributes(['disabled' => '']);
        }

        return $select;
    }

    public function render(Element $control)
    {
        $content = new Content();

        if ($this->isMultiple()) {
            $control->setName(
                $this->getName().'[]'
            );

            $control->addAttributes(['multiple' => true]);
        }

        $content->push($control);

        $values = Arr::wrap($this->getValue());

        if ($this->isReadOnly()) {
            foreach ($values as $value) {
                $name = $this->getName().($this->isMultiple() ? '[]' : '');

                $input = (new Input())
                    ->setType('hidden')
                    ->setValue($value)
                    ->setName($name);

                $input->attributes()->forget('id');

                $content->push(
                    $input
                );
            }
        }

        return $content;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getSelected(): array
    {
        return $this->selected;
    }

    public function setSelected(mixed $selected): self
    {
        $this->selected = Arr::wrap($selected);

        return $this;
    }

    /**
     * @return array
     */
    protected function buildOptions()
    {
        $selected = array_map(function ($value) {
            if ($value instanceof Model) {
                return $value->getKey();
            }

            return $value;
        }, $this->getSelected());

        $items = new  Content();

        foreach ($this->getOptions() as $key => $value) {
            $option = Html::option((string) $value)->setValue($key);

            if (in_array($key, $selected)) {
                $option->select();
            }

            $items->push($option);
        }

        return $items;
    }
}
