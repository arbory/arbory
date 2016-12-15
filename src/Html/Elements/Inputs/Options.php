<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

class Options extends Collection
{
    public function __toString()
    {
        return implode( PHP_EOL, array_map( 'strval', $this->all() ) );
    }

    public function add( Option $option )
    {
        $this->put( $option->getValue(), $option );
    }
}
