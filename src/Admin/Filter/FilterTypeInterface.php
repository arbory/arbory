<?php

namespace Arbory\Base\Admin\Filter;

/**
 * TODO: Figure out if filter type should interact with parameters directly or already filtered specifically for this filter?
 *
 *
 * Interface FilterTypeInterface
 */
interface FilterTypeInterface
{
    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @param $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return iterable
     */
    public function getConfig(): iterable;

    /**
     * @param iterable $config
     * @return FilterTypeInterface
     */
    public function setConfig(iterable $config): self;

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem);
}
