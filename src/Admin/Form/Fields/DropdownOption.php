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
     * @var array
     */
    protected $attributes;

    /**
     * @var boolean
     */
    protected $selected;


    /**
     * DropdownOption constructor.
     * @param string|int $value
     * @param string $label
     * @param array $attributes
     * @param boolean $selected
     */
    public function __construct( $value, $label, $attributes = [], $selected = false )
    {
        $this->value = $value;
        $this->label = $label;
        $this->attributes = $attributes;
        $this->selected = $selected;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return boolean
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param boolean $selected
     */
    public function setSelected( $selected )
    {
        $this->selected = $selected;
    }
}
