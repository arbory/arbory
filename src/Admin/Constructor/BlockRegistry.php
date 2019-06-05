<?php

namespace Arbory\Base\Admin\Constructor;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

class BlockRegistry
{
    /**
     * @var Collection
     */
    protected $blocks;
    /**
     * @var Container
     */
    protected $container;

    /**
     * Registry constructor.
     *
     * @param  Container  $container
     * @param  array  $blocks
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->blocks = new Collection();
    }

    /**
     * @param  string  $resource
     *
     * @return BlockInterface|null
     */
    public function findByResource(string $resource): ?BlockInterface
    {
        return $this->blocks->first(function (BlockInterface $block) use ($resource) {
            return $block->resource() === $resource;
        });
    }

    /**
     * @param  string  $block
     *
     * @return BlockRegistry
     */
    public function register(string $block): self
    {
        $value = $this->container->make($block);

        $this->blocks->put($value->name(), $value);

        return $this;
    }

    /**
     * @param  string  $block
     *
     * @return BlockInterface|null
     */
    public function resolve(string $block): ?BlockInterface
    {
        return $this->blocks->get($block);
    }

    /**
     * @return Collection|BlockInterface[]
     */
    public function all(): Collection
    {
        return $this->blocks;
    }
}
