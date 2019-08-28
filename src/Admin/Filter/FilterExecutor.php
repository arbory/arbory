<?php

namespace Arbory\Base\Admin\Filter;

use Illuminate\Database\Eloquent\Builder;
use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

class FilterExecutor
{
    /**
     * @param FilterManager $filterManager
     * @param Builder $builder
     *
     * @return Builder
     */
    public function execute(FilterManager $filterManager, Builder $builder): Builder
    {
        $parameters = $filterManager->getParameters();

        foreach ($filterManager->getFilters() as $filterItem) {
            $this->executeForItem($filterItem, $parameters, $builder);
        }

        return $builder;
    }

    /**
     * @param FilterItem $filterItem
     * @param FilterParameters $parameters
     * @param Builder $builder
     */
    protected function executeForItem(FilterItem $filterItem, FilterParameters $parameters, Builder $builder): void
    {
        if (! $parameters->has($filterItem->getName())) {
            return;
        }

        // Use user defined executor
        if ($executor = $filterItem->getExecutor()) {
            $executor($filterItem, $builder);

            return;
        }

        $type = $filterItem->getType();

        if ($type instanceof WithCustomExecutor) {
            $type->execute($filterItem, $builder);

            return;
        }

        $this->applyQuery($parameters, $filterItem, $builder);
    }

    /**
     * @param FilterParameters $parameters
     * @param FilterItem $filterItem
     * @param Builder $builder
     */
    protected function applyQuery(FilterParameters $parameters, FilterItem $filterItem, Builder $builder): void
    {
        $value = $parameters->getFromFilter($filterItem);

        if (is_array($value)) {
            $builder->whereIn($filterItem->getName(), $value);
        } else {
            $builder->where($filterItem->getName(), $value);
        }
    }
}
