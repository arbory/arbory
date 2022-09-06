<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class I.
 */
class I extends Element
{

    public function __toString(): string
    {
        return $this->tag('i', $this->content);
    }
}
