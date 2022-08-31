<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class I.
 */
class I extends Element
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->tag('i', $this->content);
    }
}
