<?php

namespace Arbory\Base\Html\Elements;

use Arbory\Base\Admin\Navigator\NavigableInterface;
use Arbory\Base\Admin\Navigator\Navigator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Content extends Collection implements Renderable
{
    protected $handlers = [
        Renderable::class => 'renderable'
    ];

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
        return $this->map([$this, 'evaluate'])->implode(PHP_EOL);
    }

    public function evaluate($value) {
        $className = null;

        if(is_object($value)) {
            $className = get_class($value);

            // TODO: Better position and detection
            if($value instanceof NavigableInterface) {
//                dump("Navigable");
                $value->navigator(app(Navigator::class));
            }

            // TODO: Check if this affects performance in a noticeable way
            foreach($this->handlers as $class => $name) {
                if(!$value instanceof $class) {
                    continue;
                }

                $method = $this->handlers[$class];

                return $this->{$method}($value);
            }

            if ( method_exists($value, '__toString') ) {
                return (string) $value;
            }
        }

        if ( is_scalar($value) || is_null($value) ) {
            return $value;
        }

        throw new \LogicException("Cannot render the contents of " . gettype($value) . " {$className}");
    }

    protected function renderable(Renderable $value)
    {
        return (string) $value->render();
    }
}
