<?php


namespace Arbory\Base\Admin\Filter;


// TODO: Consider using mixins for configuring certain filters
class FilterItem
{
    /**
     * @var string
     */
    protected $namespace;

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
     * @var callable|null
     */
    protected $executor;
    
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
    public function setDefaultValue($defaultValue): FilterItem
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

    /**
     * @return string
     */
    public function getNamespacedName(): string
    {
        return implode('.', [$this->getNamespace(), $this->getName()]);
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return FilterItem
     */
    public function setNamespace(string $namespace): FilterItem
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param callable|null $executor
     * @return FilterItem
     */
    public function setExecutor(?callable $executor): FilterItem
    {
        $this->executor = $executor;

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getExecutor(): ?callable
    {
        return $this->executor;
    }
}