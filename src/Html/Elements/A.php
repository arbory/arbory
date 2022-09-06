<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class A.
 */
class A extends Element
{
    public function __toString(): string
    {
        return $this->tag('a', $this->content);
    }
}
