<?php

namespace CubeSystems\Leaf\Admin\Layout;

use CubeSystems\Leaf\Html\Elements\Content;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Row
 * @package CubeSystems\Leaf\Admin\Layout
 */
class Row implements Renderable
{
    /**
     * @var Collection
     */
    protected $content;

    /**
     * Row constructor.
     * @param null $content
     */
    public function __construct( $content = null )
    {
        $this->content = new Collection();
        $this->content->push( $content );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @return Content
     */
    public function render()
    {
        $content = new Content();

        foreach( $this->content as $item )
        {
            $content->prepend( $item->render() );
        }

        return $content;
    }
}
