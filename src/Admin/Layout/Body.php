<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;

class Body
{
    /**
     * @var PageInterface
     */
    protected $page;

    /**
     * @var LayoutInterface
     */
    protected $target;

    /**
     * @var \Closure
     */
    protected $wrapper;

    /**
     * @var Content
     */
    protected $prepended;

    /**
     * @var Content
     */
    protected $appended;

    /**
     * Body constructor.
     *
     * @param                 $page
     * @param LayoutInterface $target
     */
    public function __construct($page, $target = null)
    {
        $this->target = $target;
        $this->page = $page;

        $this->prepended = new Content();
        $this->appended = new Content();
        $this->wrapper = function ($content) {
            return $content;
        };
    }

    /**
     * Adds a new wrapper for content.
     *
     * @param callable $wrapper
     *
     * @return Body
     */
    public function wrap(callable $wrapper): self
    {
        $call = $this->wrapper;

        $this->wrapper = function ($content) use ($call, $wrapper) {
            return $call($wrapper($content));
        };

        return $this;
    }

    /**
     * @param $content
     *
     * @return Body
     */
    public function append($content): self
    {
        $this->appended->push($content);

        return $this;
    }

    /**
     * @param $content
     *
     * @return Body
     */
    public function prepend($content): self
    {
        $this->prepended->push($content);

        return $this;
    }

    /**
     * @param $content
     *
     * @return string
     */
    public function render($content)
    {
        $call = $this->wrapper;

        return $this->prepended->render()
            .$call($content)
            .$this->appended->render();
    }

    /**
     * The target layout for this template.
     *
     * @return LayoutInterface
     */
    public function getTarget(): LayoutInterface
    {
        return $this->target;
    }

    /**
     * @param LayoutInterface $target
     *
     * @return Body
     */
    public function setTarget(LayoutInterface $target): self
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return PageInterface|null
     */
    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    /**
     * @param PageInterface $page
     *
     * @return Body
     */
    public function setPage(PageInterface $page): self
    {
        $this->page = $page;

        return $this;
    }
}
