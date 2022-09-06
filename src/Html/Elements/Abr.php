<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class Abr.
 */
class Abr extends Element
{
    public function __toString(): string
    {
        return $this->tag('abr', $this->content);
    }
}
