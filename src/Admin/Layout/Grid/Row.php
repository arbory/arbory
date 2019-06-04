<?php

namespace Arbory\Base\Admin\Layout\Grid;

use Arbory\Base\Html\Html;

class Row
{
    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var Column[]
     */
    protected $columns = [];

    /**
     * Column constructor.
     *
     * @param $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }

    /**
     * @param int $size
     * @param mixed $body
     * @param string|null $breakpoint
     *
     * @return Column
     */
    public function column($size, $body, $breakpoint = null)
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
     * @return \Arbory\Base\Html\Elements\Element
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
