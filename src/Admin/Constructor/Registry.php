<?php


namespace Arbory\Base\Admin\Constructor;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;

class Registry
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
     * @param Container $container
     * @param array     $blocks
     */
    public function __construct(Container $container, array $blocks)
    {
        $this->container = $container;
        $this->blocks = new Collection();

        foreach($blocks as $block) {
            $this->add($block);
        }
    }

    public function findByResource($resource):?array {

    }

    public function add($block):self
    {
        $value = $this->container->make($block);
        $this->blocks->put($value->name(), $value);

        return $this;
    }

    /**
     * TODO: Maybe allow aliasing?
     *
     * @param $block
     *
     * @return BlockInterface|null
     */
    public function resolve($block):?BlockInterface
    {
        return $this->blocks->get($block);
    }

    /**
     * @return Collection|BlockInterface[]
     */
    public function all():Collection
    {
        return $this->blocks;
    }
}