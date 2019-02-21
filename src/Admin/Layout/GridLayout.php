<?php


namespace Arbory\Base\Admin\Layout;

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
     * @var Body
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
     * @return $this
     */
    public function addColumn($size, $content, $breakpoint = null)
    {
        $this->grid->column($size, $content, $breakpoint);

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return GridLayout
     */
    public function setWidth(int $width): GridLayout
    {
        $this->width = $width;

        return $this;
    }
}