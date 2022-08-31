<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;

class Body
{
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
     * @param  $page
     * @param  LayoutInterface  $target
     * @param \Arbory\Base\Admin\Layout\PageInterface $page
     */
    public function __construct(protected $page, protected $target = null)
    {
        $this->prepended = new Content();
        $this->appended = new Content();
        $this->wrapper = fn($content) => $content;
    }

    /**
     * Adds a new wrapper for content.
     */
    public function wrap(callable $wrapper): self
    {
        $call = $this->wrapper;

        $this->wrapper = fn($content) => $call($wrapper($content));

        return $this;
    }

    /**
     * @param $content
     */
    public function append($content): self
    {
        $this->appended->push($content);

        return $this;
    }

    /**
     * @param $content
     */
    public function prepend($content): self
    {
        $this->prepended->push($content);

        return $this;
    }

    /**
     * @param $content
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
     */
    public function getTarget(): LayoutInterface
    {
        return $this->target;
    }

    public function setTarget(LayoutInterface $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    public function setPage(PageInterface $page): self
    {
        $this->page = $page;

        return $this;
    }
}
