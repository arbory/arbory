<?php


namespace Arbory\Base\Admin\Layout;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

class LayoutManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Current layout
     *
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @var Collection
     */
    protected $layouts;

    /**
     * LayoutManager constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->layouts = new Collection();
    }

    /**
     * Assign new page layout
     *
     * @param LayoutInterface $layout
     *
     * @return LayoutInterface
     */
    public function assign(LayoutInterface $layout)
    {
        $this->layout = $layout;

        return $layout;
    }

    /**
     * @param string $name
     * @param        $layoutClass
     *
     * @return LayoutInterface
     */
    public function make($layoutClass, $name = 'page'):LayoutInterface
    {
        $layout = $this->container->make($layoutClass);

        $this->layouts->put($name ?: $layoutClass, $layout);

        return $layout;
    }

    public function get($name):?LayoutInterface
    {
        return $this->layouts->get($name);
    }
}