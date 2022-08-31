<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Support\Str;
use Illuminate\Contracts\Container\Container;

class FilterFactory
{
    /**
     * FilterTypeFactory constructor.
     */
    public function __construct(protected Container $container)
    {
    }

    public function makeType(string $type, iterable $config): FilterTypeInterface
    {
        /**
         * @var FilterTypeInterface
         */
        $instance = $this->container->make($type);

        $instance->setConfig($config);

        return $instance;
    }

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
