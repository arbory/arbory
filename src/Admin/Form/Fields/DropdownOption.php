<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

/**
 * Class DropdownOption
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class DropdownOption
{
    /**
     * @var string|int
     */
    protected $value;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * @var bool
     */
    protected $selected;

    /**
     * @param $value
     * @param string $label
     * @param array $attributes
     * @param bool $selected
     */
    public function __construct(
        $value,
        string $label,
        array $attributes = null,
        bool $selected = false
    )
    {
        $this->value = $value;
        $this->label = $label;
        $this->attributes = $attributes;
        $this->selected = $selected;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     */
    public function setSelected( $selected )
    {
        $this->selected = $selected;
    }
}
