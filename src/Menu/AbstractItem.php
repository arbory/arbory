<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Elements\Element;

abstract class AbstractItem
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $abbreviation;

    /**
     * @var string
     */
    protected $model;

    /**
     * @param Element $parentElement
     * @return Element
     */
    abstract public function render(Element $parentElement): Element;

    /**
     * @return bool
     */
    abstract public function isAccessible(): bool;

    /**
     * @return bool
     */
    abstract public function isActive(): bool;

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $abbreviation
     * @return $this
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        if ($this->abbreviation === null) {
            $this->abbreviation = substr($this->getTitle(), 0, 2);
        }

        return $this->abbreviation;
    }
}
