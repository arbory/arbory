<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Layout\Grid\Row;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class Grid  implements Renderable
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


    /**
     * @param $content
     *
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
     * @param     $content
     *
     * @return \Arbory\Base\Html\Elements\Content
     */
    public function column( $size = 1, $content )
    {
        if(!$this->row) {
            $this->row = $this->row();
        }

        return $this->row->column($size, $content);
    }

    public function render()
    {
        $content = Html::div(null)->addClass('grid');

        foreach( $this->rows as $row)
        {
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