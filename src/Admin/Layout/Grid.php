<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Layout\Grid\Row;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Admin\Layout\Grid\Column;
use Illuminate\Contracts\Support\Renderable;

class Grid implements Renderable
{
    const SIZE_MAX = 12;

    /**
     * @var Row[]
     */
    protected $rows = [];

    /**
     * @var Row
     */
    protected $row;

    public function __construct(?callable $callable = null)
    {
        if ($callable) {
            $callable($this);
        }
    }

    /**
     * @return Row
     */
    public function row()
    {
        $this->row = new Row();

        $this->rows[] = $this->row;

        return $this->row;
    }

    /**
     * @param int $size
     * @param mixed $content
     *
     * @param string|null $breakpoint
     *
     * @return Column
     */
    public function column($size, $content, $breakpoint = null)
    {
        if (! $this->row) {
            $this->row = $this->row();
        }

        return $this->row->column($size, $content, $breakpoint);
    }

    /**
     * Returns the maximum grid row size.
     *
     * @return int
     */
    public function getRowSize()
    {
        return static::SIZE_MAX;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element|string
     */
    public function render()
    {
        $content = Html::div(null)->addClass('grid');

        foreach ($this->rows as $row) {
            $content->append(
                $row->render()
            );
        }

        return $content;
    }

    public function __toString()
    {
        return (new Content($this->render()))->__toString();
    }
}
