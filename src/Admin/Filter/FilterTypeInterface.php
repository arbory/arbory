<?php


namespace Arbory\Base\Admin\Filter;


/**
 * TODO: Figure out if filter type should interact with parameters directly or already filtered specifically for this filter?
 *
 *
 * Interface FilterTypeInterface
 * @package Arbory\Base\Admin\Filter
 */
interface FilterTypeInterface
{
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
    public function getConfiguration(): iterable;

    /**
     * @param iterable $configuration
     * @return FilterTypeInterface
     */
    public function setConfiguration(iterable $configuration): FilterTypeInterface;

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem);
}