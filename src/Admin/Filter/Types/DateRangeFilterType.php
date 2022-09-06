<?php

namespace Arbory\Base\Admin\Filter\Types;

use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;

class DateRangeFilterType extends RangeFilterType
{
    protected $inputType = 'date';

    public function execute(FilterItem $filterItem, Builder $builder): void
    {
        $min = $this->getRangeValue(static::KEY_MIN);
        $max = $this->getRangeValue(static::KEY_MAX);

        if ($min) {
            $builder->whereDate($filterItem->getName(), '>=', $min);
        }

        if ($max) {
            $builder->whereDate($filterItem->getName(), '<=', $max);
        }
    }

    public function rules(FilterParameters $parameters, callable $attributeResolver): array
    {
        return [
            static::KEY_MIN => ['nullable', 'date'],
            static::KEY_MAX => ['nullable', 'date'],
        ];
    }

    public function withValidator(
        Validator $validator,
        FilterParameters $filterParameters,
        callable $attributeResolver
    ): void {
        $minAttribute = $attributeResolver(static::KEY_MIN);
        $maxAttribute = $attributeResolver(static::KEY_MAX);

        $validator->sometimes(
            $attributeResolver(static::KEY_MIN),
            "before_or_equal:{$maxAttribute}",
            static fn (Fluent $fluent) => ! blank(Arr::get($fluent->getAttributes(), $maxAttribute))
        );

        $validator->sometimes(
            $attributeResolver(static::KEY_MAX),
            "after_or_equal:{$minAttribute}",
            static fn (Fluent $fluent) => ! blank(Arr::get($fluent->getAttributes(), $minAttribute))
        );
    }
}
