<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Html\Elements\Content;

class Body
{
    /**
     * @var LayoutInterface
     */
    protected $root;

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
     * @param LayoutInterface $root
     */
    public function __construct($root = null)
    {
        $this->root = $root;

        $this->prepended = new Content();
        $this->appended  = new Content();
        $this->wrapper   = function ($content) {
            return $content;
        };
    }

    /**
     * Adds a new wrapper for content
     *
     * @param callable $wrapper
     *
     * @return Body
     */
    public function wrap(callable $wrapper):self
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
    public function append($content):self
    {
        $this->appended->push($content);

        return $this;
    }

    /**
     * @param $content
     *
     * @return Body
     */
    public function prepend($content):self
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
            . $call($content)
            . $this->appended->render();
    }

    /**
     * @return LayoutInterface
     */
    public function getRoot(): LayoutInterface
    {
        return $this->root;
    }

    /**
     * @param LayoutInterface $root
     *
     * @return Body
     */
    public function setRoot(LayoutInterface $root): self
    {
        $this->root = $root;

        return $this;
    }
}