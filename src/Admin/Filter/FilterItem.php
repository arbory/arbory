<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Support\Traits\Macroable;

class FilterItem
{
    use Macroable;

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
     * @var FilterManager
     */
    protected $manager;

    /**
     * @var bool|null
     */
    protected $isOpen;

    /**
     * @var object
     */
    protected $owner;

    /**
     * @param string $name
     * @return FilterItem
     */
    public function setName(string $name): self
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
    public function setTitle(string $title): self
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
    public function setType(FilterTypeInterface $type): self
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
    public function setDefaultValue($defaultValue): self
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
        return $this->getManager()->getParameters()->getNamespace();
    }

    /**
     * @param callable|null $executor
     * @return FilterItem
     */
    public function setExecutor(?callable $executor): self
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

    /**
     * @param FilterManager $manager
     * @return FilterItem
     */
    public function setManager(FilterManager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return FilterManager
     */
    public function getManager(): FilterManager
    {
        return $this->manager;
    }

    /**
     * @param bool $isOpen
     * @return FilterItem
     */
    public function setIsOpen(?bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    /**
     * @param object $owner
     * @return FilterItem
     */
    public function setOwner($owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return object
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
