<?php

namespace CubeSystems\Leaf\Html\Elements;

use Illuminate\Support\Collection;

class Content extends Collection
{
    public function __toString()
    {
        return implode( PHP_EOL, array_map( 'strval', $this->all() ) );
    }
}
