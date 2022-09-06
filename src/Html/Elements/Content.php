<?php

namespace Arbory\Base\Html\Elements;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use LogicException;

class Content extends Collection implements Renderable
{
    protected $handlers = [
        Renderable::class => 'renderable',
    ];

    /**
     * @return string
     */
    public function __toString(): string
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

    /**
     * @param $value
     * @return string
     */
    public function evaluate($value)
    {
        $className = null;

        if (is_object($value)) {
            $className = $value::class;

            foreach ($this->handlers as $class => $name) {
                if (! $value instanceof $class) {
                    continue;
                }

                $method = $this->handlers[$class];

                return $this->{$method}($value);
            }

            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
        }

        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        throw new LogicException('Cannot render the contents of ' . gettype($value) . " {$className}");
    }

    /**
     * @return string
     */
    protected function renderable(Renderable $value)
    {
        return (string)$value->render();
    }
}
