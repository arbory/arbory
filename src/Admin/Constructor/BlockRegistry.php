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
     * Registry constructor.
     *
     * @param  array  $blocks
     */
    public function __construct(protected Container $container)
    {
        $this->blocks = new Collection();
    }

    public function findByResource(string $resource): ?BlockInterface
    {
        return $this->blocks->first(fn(BlockInterface $block) => $block->resource() === $resource);
    }

    public function register(string $block): self
    {
        $value = $this->container->make($block);

        $this->blocks->put($value->name(), $value);

        return $this;
    }

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
