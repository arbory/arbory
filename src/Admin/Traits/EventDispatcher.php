<?php

namespace Arbory\Base\Admin\Traits;

use Closure;
use Illuminate\Support\Arr;

/**
 * Class EventDispatcher.
 */
trait EventDispatcher
{
    /**
     * @var array
     */
    protected $eventListeners = [];

    /**
     * @param $event
     * @param  array  ...$parameters
     */
    protected function trigger($event, ...$parameters)
    {
        foreach ($this->getEventListeners($event) as $listener) {
            $listener(...$parameters);
        }
    }

    /**
     * @param $event
     */
    public function on($event, Closure $callback)
    {
        $this->addEventListener($event, $callback);
    }

    public function addEventListeners(array $events, Closure $callback)
    {
        foreach ((array) $events as $event) {
            $this->addEventListener($event, $callback);
        }
    }

    /**
     * @param $event
     */
    public function addEventListener($event, Closure $callback)
    {
        $this->eventListeners[$event][] = $callback;
    }

    /**
     * @param $event
     * @return array
     */
    public function getEventListeners($event)
    {
        return Arr::get($this->eventListeners, $event, []);
    }
}
