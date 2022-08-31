<?php

namespace Arbory\Base\Html\Elements;

class Ul extends Element
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->tag('ul', $this->content);
    }
}
