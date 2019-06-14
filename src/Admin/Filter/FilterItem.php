<?php


namespace Arbory\Base\Admin\Filter;


class FilterItem
{
    /**
     * @var FilterTypeInterface
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @param string $name
     * @return FilterItem
     */
    public function setName(string $name): FilterItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $title
     * @return FilterItem
     */
    public function setTitle(string $title): FilterItem
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param FilterTypeInterface $type
     * @return FilterItem
     */
    public function setType(FilterTypeInterface $type): FilterItem
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return FilterTypeInterface
     */
    public function getType(): FilterTypeInterface
    {
        return $this->type;
    }

    /**
     * @param mixed $defaultValue
     * @return FilterItem
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}