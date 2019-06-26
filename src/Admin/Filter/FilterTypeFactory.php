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
     * @param iterable $config
     * @return FilterTypeInterface
     */
    public function make(string $type, iterable $config): FilterTypeInterface
    {
        /**
         * @var $instance FilterTypeInterface
         */
        $instance = $this->container->make($type);

        $instance->setConfig($config);

        return $instance;
    }
}