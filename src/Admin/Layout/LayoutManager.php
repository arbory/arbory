<?php

namespace Arbory\Base\Admin\Layout;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

class LayoutManager
{
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
     */
    public function __construct(protected Container $container)
    {
        $this->layouts = new Collection();
    }

    /**
     * Creates a new main page.
     *
     * @param $pageClass
     */
    public function page($pageClass): \Arbory\Base\Admin\Layout\LayoutInterface|\Arbory\Base\Admin\Layout\PageInterface
    {
        $this->page = $this->make($pageClass, 'page');

        return $this->page;
    }

    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    /**
     * @param  string|null  $name
     * @param  string  $layoutClass
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
