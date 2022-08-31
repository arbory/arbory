<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Admin\Layout\Grid\Column;
use Illuminate\Support\Arr;

class GridLayout extends AbstractLayout implements LayoutInterface
{
    /**
     * @var int
     */
    protected $width = 12;

    /**
     * @var array
     */
    protected $breakpoints = [];

    /**
     * @var Column
     */
    protected $column;

    /**
     * GridTemplate constructor.
     */
    public function __construct(protected Grid $grid)
    {
        $this->column = $this->grid->column($this->width, '');
    }

    public function build()
    {
        $this->column->size($this->getWidth());

        if ($this->breakpoints) {
            $this->column->breakpoints($this->breakpoints);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function contents($content)
    {
        $this->column->set($content);

        return $this->grid->render();
    }

    /**
     * @param  $size
     * @param  $content
     * @param  null  $breakpoint
     */
    public function addColumn($size, $content, $breakpoint = null): Column
    {
        return $this->grid->column($size, $content, $breakpoint);
    }

    public function getColumn(): Column
    {
        return $this->column;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width, ?array $breakpoints = null): self
    {
        $this->width = $width;
        $this->breakpoints = array_merge(
            [Column::BREAKPOINT_DEFAULT => $width],
            Arr::wrap($breakpoints)
        );

        return $this;
    }

    public function getBreakpoints(): ?array
    {
        return $this->breakpoints;
    }

    public function setBreakpoints(array $breakpoints): self
    {
        $this->breakpoints = $breakpoints;

        return $this;
    }
}
