<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Slot implements Renderable
{
    /**
     * @var Collection|Slot[]
     */
    protected $children;

    protected $wrap;

    /**
     * Slot constructor.
     *
     * @param string $name
     * @param null $contents
     */
    public function __construct(protected string $name, protected $contents = null)
    {
        $this->children = collect();
    }

    public function name()
    {
        return $this->name;
    }

    public function children(): Collection
    {
        return $this->children;
    }

    /**
     * @param $name
     * @param $contents
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
     * @return Slot
     */
    public function getChild($name)
    {
        return $this->children->get($name);
    }

    public function render(): Content|string
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
                static fn (self $value) => $value->render()
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
     * @return Slot
     */
    public function setContents(mixed $contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
