<?php


namespace Arbory\Base\Admin\Filter;


use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Illuminate\Database\Eloquent\Builder;

class FilterExecutor
{
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * FilterExecutor constructor.p
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(FilterBuilder $filterBuilder)
    {
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @param Builder $builder
     */
    public function execute(Builder $builder)
    {
        $filters = $this->filterBuilder->getFilters();
        $parameters = $this->filterBuilder->getParameters();

        foreach($filters as $filterItem) {
            if(! $parameters->has($filterItem->getName())) {
                continue;
            }

            // Use user defined executor
            if($executor = $filterItem->getExecutor()) {
                $executor($filterItem, $builder);

                continue;
            }

            $type = $filterItem->getType();

            if($type instanceof WithCustomExecutor) {
                $type->execute($filterItem, $builder);
            } else {
                $value = $parameters->get($filterItem->getName());

                if(is_array($value)) {
                    $builder->whereIn($filterItem->getName(), $value);
                } else {
                    $builder->where($filterItem->getName(), $value);
                }
            }
        }
    }
}