<?php

namespace CubeSystems\Leaf\Html\Elements;

/**
 * Class I
 * @package CubeSystems\Leaf\Html\Elements
 */
class I extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag( 'i', $this->content );
    }
}