<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Contracts\Container\Container;

class LayoutResolver
{
    /**
     * @var LayoutInterface
     */
    protected $instance;

    /**
     * @var string
     */
    protected $layoutClass;

    /**
     * @var LayoutResolver[]
     */
    protected $use = [];

    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var array
     */
    protected $slots;

    /**
     * LayoutResolver constructor.
     *
     * @param  $layout
     */
    public function __construct(protected Container $container, $layout)
    {
        if ($layout instanceof LayoutInterface) {
            $this->instance = $layout;
            $this->layoutClass = $layout::class;
        } else {
            $this->layoutClass = $layout;
        }
    }

    /**
     * @return $this
     */
    public function handle(callable $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @param $layout
     * @param $parameters
     */
    public function with($layout): self
    {
        $resolver = new self($this->container, $layout);

        $this->use[] = $resolver;

        return $resolver;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function slot($name, mixed $content)
    {
        $className = $this->layoutClass;
        $allowedSlots = $className::SLOTS;

        if (! in_array($name, $allowedSlots, true)) {
            throw new \RuntimeException("Slot '{$name}' does not exist in layout '{$className}'");
        }

        $this->slots[$name] = $content;

        return $this;
    }

    /**
     * @param  $next
     * @return mixed
     */
    public function __invoke(Body $body, $next, array ...$parameters)
    {
        return $this->resolve()->apply($body, $next, ...$parameters);
    }

    public function resolve(): LayoutInterface
    {
        $layout = $this->instance ?: $this->create();

        if ($this->handler) {
            $layout = call_user_func($this->handler, $layout) ?: $layout;
        }

        foreach ($this->use as $resolver) {
            $layout->use($resolver);
        }

        foreach ($this->slots as $name => $content) {
            $layout->slot($name, $content);
        }

        return $layout;
    }

    public function create(): LayoutInterface
    {
        return $this->container->make($this->layoutClass);
    }
}
