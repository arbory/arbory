<?php

namespace CubeSystems\Leaf\Builders;

class Breadcrumbs
{
    /**
     * @var array
     */
    protected $links = [];

    /**
     * @param $title
     * @param $link
     */
    public function add( $title, $link )
    {
        $this->links[] = [
            'title' => $title,
            'link' => $link,
        ];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->links;
    }
}
