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
     * @var Container
     */
    protected $container;

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
     * @param Container $container
     * @param           $layout
     */
    public function __construct(Container $container, $layout)
    {
        $this->container = $container;

        if ($layout instanceof LayoutInterface) {
            $this->instance = $layout;
            $this->layoutClass = get_class($layout);
        } else {
            $this->layoutClass = $layout;
        }
    }

    /**
     * @param callable $handler
     *
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
     *
     * @return LayoutResolver
     */
    public function with($layout): self
    {
        $resolver = new self($this->container, $layout);

        $this->use[] = $resolver;

        return $resolver;
    }

    /**
     * @param string $name
     * @param mixed $content
     * @return $this
     */
    public function slot($name, $content)
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
     * @param Body  $body
     * @param       $next
     * @param array ...$parameters
     *
     * @return mixed
     */
    public function __invoke(Body $body, $next, array ...$parameters)
    {
        return $this->resolve()->apply($body, $next, ...$parameters);
    }

    /**
     * @return LayoutInterface
     */
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

    /**
     * @return LayoutInterface
     */
    public function create(): LayoutInterface
    {
        return $this->container->make($this->layoutClass);
    }
}
