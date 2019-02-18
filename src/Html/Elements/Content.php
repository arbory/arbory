<?php

namespace Arbory\Base\Html\Elements;

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
        return $this->map(function ( $value ) {
            $className = null;

            if ( $value instanceof Renderable ) {
                return (string) $value->render();
            }

            if ( is_object($value) ) {
                if ( method_exists($value, '__toString') ) {
                    return (string) $value;
                }

                $className = get_class($value);
            }

            if ( is_scalar($value) || is_null($value) ) {
                return $value;
            }

            dump($value);

            throw new \LogicException("Cannot render the contents of " . gettype($value) . " {$className}");
        })->implode(PHP_EOL);
    }
}
