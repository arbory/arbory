<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Contracts\Support\Renderable;

class LazyRenderer implements Renderable
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * LazyRenderer constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return call_user_func($this->callable);
    }
}
