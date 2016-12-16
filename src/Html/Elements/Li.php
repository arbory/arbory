<?php

namespace CubeSystems\Leaf\Html\Elements;

class Li extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag( 'li', $this->content );
    }
}