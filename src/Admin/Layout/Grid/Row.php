<?php


namespace Arbory\Base\Admin\Layout\Grid;


use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;

class Row
{
    protected $content;

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
     * @param $size
     * @param $content
     *
     * @return Content
     */
    public function column($size,  $body )
    {
        $content = new Column($size, $body);

        $this->columns[] = $content;

        return $content;
    }

    public function render()
    {
        $content = Html::div()->addClass('row');

        foreach( $this->columns as $col) {
            $content->append(
                $col->render()
            );
        }

        return $content;
    }
}