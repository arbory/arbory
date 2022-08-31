<?php

namespace Arbory\Base\Admin\Filter;

/**
 * TODO: Figure out if filter type should interact with parameters directly
 * or already filtered specifically for this filter?
 *
 *
 * Interface FilterTypeInterface
 */
interface FilterTypeInterface
{
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

    public function getConfig(): iterable;

    public function setConfig(iterable $config): self;

    /**
     * @return mixed
     */
    public function render(FilterItem $filterItem);
}
