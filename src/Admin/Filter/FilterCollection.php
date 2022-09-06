<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Support\Collection;

class FilterCollection extends Collection
{
    public function findByConcerns(array $concerns = []): self
    {
        return $this->filter(static function (FilterItem $filterItem) use ($concerns) {
            $implements = array_values(class_implements($filterItem->getType()));

            return count(array_intersect($concerns, $implements)) === count($concerns);
        });
    }

    public function findByOwner($owner): self
    {
        return $this->filter(static fn (FilterItem $filterItem) => $owner === $filterItem->getOwner());
    }

    public function findWithoutOwners(): self
    {
        return $this->findByOwner(null);
    }
}
