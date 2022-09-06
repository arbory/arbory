<?php

namespace Arbory\Base\Html\Elements;

class Ul extends Element
{
    public function __toString(): string
    {
        return $this->tag('ul', $this->content);
    }
}
