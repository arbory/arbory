<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

class GridTemplate extends AbstractLayout implements LayoutInterface
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

    public function contents($content)
    {
        $this->column->set($content);

        return $this->grid->render();
    }


    public function column($size, $content)
    {
        $this->grid->column($size, $content);

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
     * @return GridTemplate
     */
    public function setWidth(int $width): GridTemplate
    {
        $this->width = $width;

        return $this;
    }
}