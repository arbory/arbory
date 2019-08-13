<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Support\Collection;
use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;

class Slot implements Renderable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Illuminate\Support\Collection|Slot[]
     */
    protected $children;

    /**
     * @var mixed
     */
    protected $contents;

    protected $wrap;

    /**
     * Slot constructor.
     *
     * @param      $name
     * @param null $contents
     */
    public function __construct($name, $contents = null)
    {
        $this->name = $name;
        $this->contents = $contents;
        $this->children = collect();
    }

    public function name()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function children(): Collection
    {
        return $this->children;
    }

    /**
     * @param $name
     * @param $contents
     *
     * @return Slot
     */
    public function setChild($name, $contents)
    {
        $slot = new static($name, $contents);
        $this->children->put($name, $slot);

        return $slot;
    }

    /**
     * @param $name
     *
     * @return Slot
     */
    public function getChild($name)
    {
        return $this->children->get($name);
    }

    /**
     * @return Content|string
     */
    public function render()
    {
        if (is_callable($this->contents)) {
            $content = $this->contents;
            $content = $content();
        } else {
            $content = value($this->contents);
        }

        $contents = new Content(
            [
                $content,
            ]
        );

        $contents = $contents->merge(
            $this->children->map(
                static function (self $value) {
                    return $value->render();
                }
            )
        );

        return $this->wrap ? ($this->wrap)($contents) : $contents;
    }

    /**
     * @return null|callable
     */
    public function getWrap()
    {
        return $this->wrap;
    }

    /**
     * @param mixed $wrap
     *
     * @return Slot
     */
    public function setWrap(?callable $wrap): self
    {
        $this->wrap = $wrap;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     *
     * @return Slot
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
