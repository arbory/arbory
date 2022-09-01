<?php

namespace Arbory\Base\Admin\Layout\Grid;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class Row
{
    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * Column constructor.
     *
     * @param $content
     */
    public function __construct(protected mixed $content = null)
    {
    }

    /**
     * @param  int  $size
     * @param  string|null  $breakpoint
     * @return Column
     */
    public function column($size, mixed $body, $breakpoint = null)
    {
        $content = new Column($size, $body, $breakpoint);

        $this->columns[] = $content;

        return $content;
    }

    public function addColumn(Column $column)
    {
        $this->columns[] = $column;

        return $column;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $content = Html::div()->addClass('grid-row');

        foreach ($this->columns as $col) {
            $content->append(
                $col->render()
            );
        }

        return $content;
    }
}
