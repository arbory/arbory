<?php

namespace Arbory\Base\Admin\Filter;

/**
 * Class Type
 * @package Arbory\Base\Admin\Filter
 */
class Type
{
    /**
     * @var
     */
    protected $action;

    /**
     * @return array
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * @return string|null
     */
    public function getCheckboxStatus()
    {
        if ($this->request->has($this->column->getName())) {
            return 'checked';
        }

        return null;
    }

    /**
     * @param $valueSide
     * @return string|null
     */
    public function getRangeValue($valueSide)
    {
        $name = $this->column->getName();
        if (!$this->request->has($name)) {
            return null;
        }

        $value = $this->request->get($name);

        if ($value[$valueSide]) {
            return 'value="' . $value[$valueSide] . '"';
        }

        return null;
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getCheckboxStatusFromArray($value)
    {
        if ($this->request->has($this->column->getName())) {
            $array = $this->request->get($this->column->getName());

            foreach ($array as $item) {
                if ($item === $value) {
                    return 'checked';
                }
            }
        }

        return null;
    }
}