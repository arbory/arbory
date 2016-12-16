<?php

namespace CubeSystems\Leaf\Html\Elements;

/**
 * Class A
 * @package CubeSystems\Leaf\Html\Elements
 */
class A extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        $tag = $this->tag( 'a', $this->content );

        return (string) $tag;
    }
}