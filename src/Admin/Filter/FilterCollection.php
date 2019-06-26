<?php


namespace Arbory\Base\Admin\Filter;


use Illuminate\Support\Collection;

class FilterCollection extends Collection
{
    /**
     * @param string[] $concerns
     * @return FilterCollection
     */
    public function findByConcerns(array $concerns): FilterCollection
    {
        return $this->filter(static function(FilterItem $filterItem) use ($concerns) {
            $implements = array_values(class_implements($filterItem->getType()));

            return count(array_intersect($concerns, $implements)) === count($concerns);
        });
    }
}