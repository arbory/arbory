<?php


namespace Arbory\Base\Admin\Filter;


use Illuminate\Support\Collection;

class FilterCollection extends Collection
{
    /**
     * @var FilterItem[]
     */
    protected $items = [];

    /**
     * @param string[] $concerns
     * @return FilterCollection|FilterItem[]
     */
    public function findByConcerns(array $concerns): FilterCollection
    {
        return $this->filter(static function (FilterItem $filterItem) use ($concerns) {
            $implements = array_values(class_implements($filterItem->getType()));

            return count(array_intersect($concerns, $implements)) === count($concerns);
        });
    }

    /**
     * @param $owner
     * @return FilterCollection|FilterItem[]
     */
    public function findByOwner($owner): FilterCollection
    {
        return $this->filter(static function (FilterItem $filterItem) use ($owner) {
            return $owner === $filterItem->getOwner();
        });
    }

    /**
     * @return FilterCollection
     */
    public function findWithoutOwners(): FilterCollection
    {
        return $this->findByOwner(null);
    }
}