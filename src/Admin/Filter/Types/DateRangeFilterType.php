<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterParameters;
use Illuminate\Database\Eloquent\Builder;

class DateRangeFilterType extends RangeFilterType implements WithCustomExecutor, WithParameterValidation
{
    protected $inputType = 'date';

    public function execute(FilterItem $filterItem, Builder $builder)
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
     * TODO: Laravel validator & Validation support for multi level parameters
     *
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