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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setType(FilterTypeInterface $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): FilterTypeInterface
    {
        return $this->type;
    }

    public function setDefaultValue(mixed $defaultValue): self
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

    public function setExecutor(?callable $executor): self
    {
        $this->executor = $executor;

        return $this;
    }

    public function getExecutor(): ?callable
    {
        return $this->executor;
    }

    public function setManager(FilterManager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getManager(): FilterManager
    {
        return $this->manager;
    }

    /**
     * @param  bool  $isOpen
     */
    public function setIsOpen(?bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    /**
     * @param  object  $owner
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
