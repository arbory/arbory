<?php

namespace CubeSystems\Leaf\Html\Elements;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Content extends Collection implements Renderable
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        return implode( PHP_EOL, array_map( 'strval', $this->all() ) );
    }
}
