<?php

namespace Arbory\Base\Html\Elements;

class Ul extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag('ul', $this->content);
    }
}
