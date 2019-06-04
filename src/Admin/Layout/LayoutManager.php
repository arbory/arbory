<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

class LayoutManager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Current layout.
     *
     * @var PageInterface
     */
    protected $page;

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
     * Creates a new main page.
     *
     * @param $pageClass
     *
     * @return LayoutInterface|PageInterface
     */
    public function page($pageClass)
    {
        $this->page = $this->make($pageClass, 'page');

        return $this->page;
    }

    /**
     * @return PageInterface|null
     */
    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    /**
     * @param string|null $name
     * @param string $layoutClass
     *
     * @return LayoutInterface
     */
    public function make($layoutClass, $name = null): LayoutInterface
    {
        $layout = $this->container->make($layoutClass);

        $this->layouts->put($name ?: $layoutClass, $layout);

        return $layout;
    }

    public function get($name): ?LayoutInterface
    {
        return $this->layouts->get($name);
    }
}
