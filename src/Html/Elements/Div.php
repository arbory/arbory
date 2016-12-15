<?php

namespace CubeSystems\Leaf\Html\Elements;

/**
 * Class Div
 * @package CubeSystems\Leaf\Html\Elements
 */
class Div extends Element
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag( 'div', $this->content );
    }
}
