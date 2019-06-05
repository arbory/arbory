<?php

namespace Arbory\Base\Admin\Traits;

use Closure;

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
     * @param array ...$parameters
     */
    protected function trigger($event, ...$parameters)
    {
        foreach ($this->getEventListeners($event) as $listener) {
            $listener(...$parameters);
        }
    }

    /**
     * @param $event
     * @param Closure $callback
     */
    public function on($event, Closure $callback)
    {
        $this->addEventListener($event, $callback);
    }

    /**
     * @param array $events
     * @param Closure $callback
     */
    public function addEventListeners(array $events, Closure $callback)
    {
        foreach ((array) $events as $event) {
            $this->addEventListener($event, $callback);
        }
    }

    /**
     * @param $event
     * @param Closure $callback
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
        return array_get($this->eventListeners, $event, []);
    }
}
