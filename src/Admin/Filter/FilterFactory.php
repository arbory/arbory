<?php


namespace Arbory\Base\Admin\Filter;


use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

class FilterFactory
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
    public function makeType(string $type, iterable $config): FilterTypeInterface
    {
        /**
         * @var $instance FilterTypeInterface
         */
        $instance = $this->container->make($type);

        $instance->setConfig($config);

        return $instance;
    }

    /**
     * @param string $typeClass
     * @param string $name
     * @param iterable $config
     * @return FilterItem
     */
    public function makeSimpleFilter(string $typeClass, string $name, iterable $config): FilterItem
    {
        $item = $this->newInstance();

        $item->setType($this->makeType($typeClass, $config));
        $item->setName($name);
        $item->setTitle(Str::camel($name));

        return $item;
    }

    /**
     * @return FilterItem
     */
    protected function newInstance()
    {
        return new FilterItem();
    }
}