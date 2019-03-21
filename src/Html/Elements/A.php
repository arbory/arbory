<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class A
 * @package Arbory\Base\Html\Elements
 */
class A extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        $tag = $this->tag('a', $this->content);

        return (string)$tag;
    }
}
