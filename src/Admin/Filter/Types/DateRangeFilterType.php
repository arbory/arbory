<?php


namespace Arbory\Base\Admin\Filter\Types;

use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Illuminate\Database\Eloquent\Builder;

class DateRangeFilterType extends RangeFilterType
{
    protected $inputType = 'date';

    /**
     * @param FilterItem $filterItem
     * @param Builder $builder
     */
    public function execute(FilterItem $filterItem, Builder $builder): void
    {
        $min = $this->getRangeValue(static::KEY_MIN);
        $max = $this->getRangeValue(static::KEY_MAX);

        if($min) {
            $builder->whereDate($filterItem->getName(), '>=', $min);
        }

        if($max) {
            $builder->whereDate($filterItem->getName(), '<', $max);
        }
    }

    /**
     * @param FilterParameters $parameters
     * @param callable $attributeResolver
     * @return array
     */
    public function rules(FilterParameters $parameters, callable $attributeResolver): array
    {
        $minAttribute = $attributeResolver(static::KEY_MIN);
        $maxAttribute = $attributeResolver(static::KEY_MAX);

        return [
            static::KEY_MIN => ['nullable', 'date', "before:{$maxAttribute}"],
            static::KEY_MAX => ['nullable', 'date', "after:{$minAttribute}"]
        ];
    }
}