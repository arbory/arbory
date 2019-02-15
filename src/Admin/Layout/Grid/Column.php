<?php


namespace Arbory\Base\Admin\Layout\Grid;


use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

class Column
{
    /**
     * @var Content
     */
    protected $content;

    /**
     * @var
     */
    protected $size;

    /**
     * Row constructor.
     *
     * @param $size
     * @param $content
     */
    public function __construct($size, $content)
    {
        $this->content = new Content($content);
        $this->size = $size;
    }

    public function push($content)
    {
        $this->content->push($content);
    }

    public function render()
    {
        return Html::div(
            new Content($this->content)
        )->addClass("col-md-{$this->size}");
    }

    public function size($size)
    {
        $this->size = $size;

        return $this;
    }
}