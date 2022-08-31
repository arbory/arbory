<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class A.
 */
class A extends Element
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        $tag = $this->tag('a', $this->content);

        return (string)$tag;
    }
}
