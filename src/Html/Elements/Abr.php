<?php

namespace Arbory\Base\Html\Elements;

/**
 * Class Abr
 * @package Arbory\Base\Html\Elements
 */
class Abr extends Element
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tag( 'abr', $this->content );
    }
}
