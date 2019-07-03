<?php


namespace Arbory\Base\Admin\Filter;


use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Illuminate\Database\Eloquent\Builder;

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
        $filters = $filterManager->getFilters();
        $parameters = $filterManager->getParameters();

        foreach ($filters as $filterItem) {
            if (! $parameters->has($filterItem->getName())) {
                continue;
            }

            // Use user defined executor
            if ($executor = $filterItem->getExecutor()) {
                $executor($filterItem, $builder);

                continue;
            }

            $type = $filterItem->getType();

            if ($type instanceof WithCustomExecutor) {
                $type->execute($filterItem, $builder);
            } else {
                $this->applyQuery($filterManager->getParameters(), $filterItem, $builder);
            }
        }

        return $builder;
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