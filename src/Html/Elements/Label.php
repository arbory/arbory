<?php

namespace CubeSystems\Leaf\Html\Elements;

class Label extends Element
{
    protected $content;

    public function __construct( $for, $text )
    {
        $this->text = $text;

        $this->attributes()->put( 'for', $for );
    }

    public function __toString()
    {
        return (string) $this->tag( 'label', $this->text );
    }
}
