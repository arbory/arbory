<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Admin\Layout\Grid\Column;

class GridLayout extends AbstractLayout implements LayoutInterface
{
    /**
     * @var Grid
     */
    protected $grid;

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
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

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
     * @param      $size
     * @param      $content
     * @param null $breakpoint
     *
     * @return Column
     */
    public function addColumn($size, $content, $breakpoint = null): Column
    {
        return $this->grid->column($size, $content, $breakpoint);
    }

    /**
     * @return Column
     */
    public function getColumn(): Column
    {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int        $width
     * @param array|null $breakpoints
     *
     * @return GridLayout
     */
    public function setWidth(int $width, ?array $breakpoints = null): self
    {
        $this->width = $width;
        $this->breakpoints = array_merge(
            [Column::BREAKPOINT_DEFAULT => $width],
            array_wrap($breakpoints)
        );

        return $this;
    }

    /**
     * @return null|array
     */
    public function getBreakpoints(): ?array
    {
        return $this->breakpoints;
    }

    /**
     * @param array $breakpoints
     *
     * @return GridLayout
     */
    public function setBreakpoints(array $breakpoints): self
    {
        $this->breakpoints = $breakpoints;

        return $this;
    }
}
