<?php

namespace CubeSystems\Leaf\Html\Elements;

/**
 * Class Abr
 * @package CubeSystems\Leaf\Html\Elements
 */
class Abr extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag( 'abr', $this->content );
    }
}