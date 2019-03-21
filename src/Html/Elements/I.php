<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class I
 * @package Arbory\Base\Html\Elements
 */
class I extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->tag('i', $this->content);
    }
}
