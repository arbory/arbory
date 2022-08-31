<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Row.
 */
class Row implements Renderable
{
    /**
     * @var Collection
     */
    protected $content;

    /**
     * Row constructor.
     *
     * @param mixed|null $content
     */
    public function __construct(mixed $content = null)
    {
        $this->content = new Collection();
        $this->content->push($content);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    public function render(): Content
    {
        $content = new Content();

        foreach ($this->content as $item) {
            $content->prepend($item->render());
        }

        return $content;
    }
}
