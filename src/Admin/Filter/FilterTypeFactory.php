<?php


namespace Arbory\Base\Admin\Filter;


use Illuminate\Contracts\Container\Container;

class FilterTypeFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * FilterTypeFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $type
     * @param iterable $configuration
     * @return FilterTypeInterface
     */
    public function make(string $type, iterable $configuration): FilterTypeInterface
    {
        $instance = $this->container->make($type);

        $instance->setConfiguration($configuration);

        return $instance;
    }
}