<?php

namespace Arbory\Base\Html\Elements;

class Li extends Element
{

    public function __toString(): string
    {
        return $this->tag('li', $this->content);
    }
}
