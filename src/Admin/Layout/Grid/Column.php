<?php

namespace Arbory\Base\Admin\Layout\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Contracts\Support\Renderable;

class Column implements Renderable
{
    const BREAKPOINT_XS = 'xs';
    const BREAKPOINT_SM = 'sm';
    const BREAKPOINT_MD = 'md';
    const BREAKPOINT_LG = 'lg';

    const BREAKPOINT_DEFAULT = self::BREAKPOINT_XS;

    /**
     * @var Content
     */
    protected $content;

    /**
     * @var array
     */
    protected $breakpoints = [];

    /**
     * @var string
     */
    protected $format = 'col-{breakpoint}-{size}';

    /**
     * Row constructor.
     *
     * @param int $size
     * @param mixed $content
     * @param string $breakpoint
     */
    public function __construct($size, $content, $breakpoint = self::BREAKPOINT_DEFAULT)
    {
        $breakpoint = $breakpoint ?? static::BREAKPOINT_XS;

        $this->content = $content;

        $this->breakpoints = [
            $breakpoint => $size,
        ];
    }

    /**
     * Add breakpoints
     * Expected format:
     *  Breakpoint => Size.
     *
     * @param array $breakpoints
     *
     * @return Column
     */
    public function breakpoints(array $breakpoints): self
    {
        $this->breakpoints = $breakpoints;

        return $this;
    }

    /**
     * @param $content
     *
     * @return Column
     */
    public function push($content): self
    {
        $this->content->push($content);

        return $this;
    }

    /**
     * @param $content
     *
     * @return Column
     */
    public function set($content): self
    {
        $this->content = new Content($content);

        return $this;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div()->append($this->content)->addClass($this->buildClasses());
    }

    /**
     * Column size.
     *
     * @param int $size
     *
     * @param string $breakpoint
     *
     * @return Column
     */
    public function size($size, $breakpoint = self::BREAKPOINT_XS): self
    {
        $this->breakpoints[$breakpoint] = $size;

        return $this;
    }

    protected function buildClasses()
    {
        $classes = [];

        foreach ($this->breakpoints as $breakpoint => $size) {
            $classes[] = str_replace([
                '{breakpoint}',
                '{size}',
            ], [$breakpoint, $size], $this->format);
        }

        return implode(' ', $classes);
    }
}
