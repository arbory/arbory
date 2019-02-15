<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

class GridTemplate implements LayoutInterface
{
    protected $grid;


    protected $size = 12;

    protected $column;

    /**
     * GridTemplate constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->column = $this->grid->column($this->size, '');
    }

    /**
     * @param $content
     *
     * @return mixed
     */
    public function content($content)
    {
        // TODO: Implement content() method.
    }

    public function use(LayoutInterface $layout)
    {
        // TODO: Implement use() method.
    }

    public function apply(Content $content, Closure $next, ...$parameters)
    {
        [$size] = array_replace([$this->size],$parameters);

        $this->column->size($size);
        $this->column->push($content);

        return $next(
            new Content([$this->grid])
        );
    }

    public function column($size, $content)
    {
        $this->grid->column($size, $content);

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return GridTemplate
     */
    public function setSize(int $size): GridTemplate
    {
        $this->size = $size;
        return $this;
}
}