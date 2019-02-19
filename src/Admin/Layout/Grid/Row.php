<?php


namespace Arbory\Base\Admin\Layout\Grid;


use Arbory\Base\Html\Elements\Content;
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
     * @param $size
     * @param $content
     *
     * @return Column
     */
    public function column($size,  $body )
    {
        $content = new Column($size, $body);

        $this->columns[] = $content;

        return $content;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $content = Html::div()->addClass('grid-row');

        foreach( $this->columns as $col) {
            $content->append(
                $col->render()
            );
        }

        return $content;
    }
}