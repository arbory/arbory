<?php

namespace CubeSystems\Leaf;

/**
 * Class Breadcrumbs
 * @package CubeSystems\Leaf
 */
class Breadcrumbs
{
    /**
     * @var array
     */
    protected $items = [ ];

    /**
     * @param $title
     * @param null $link
     */
    public function add( $title, $link = null )
    {
        $this->items[] = [
            'title' => $title,
            'link' => $link,
        ];
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->items;
    }
}
