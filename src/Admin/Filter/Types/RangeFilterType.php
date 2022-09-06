<?php

namespace Arbory\Base\Admin\Filter\Types;

use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterTypeInterface;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Arbory\Base\Exceptions\BadMethodCallException;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;

class RangeFilterType extends AbstractType implements FilterTypeInterface, WithCustomExecutor, WithParameterValidation
{
    public const KEY_MIN = 'min';
    public const KEY_MAX = 'max';

    protected string $inputType = 'number';

    /**
     * @param FilterItem $filterItem
     * @return Element
     *
     * @throws BadMethodCallException
     */
    public function render(FilterItem $filterItem): Element
    {
        $step = $this->config['step'] ?? '.01';

        return Html::div([
            Html::div([
                Html::h4(trans('arbory::filter.range.from')),
                Html::input()
                    ->setType($this->inputType)
                    ->setName($filterItem->getNamespacedName() . '.' . static::KEY_MIN)
                    ->addAttributes(['step' => $step, 'value' => $this->getRangeValue(static::KEY_MIN)]),
            ])->addClass('column'),
            Html::div([
                Html::h4(trans('arbory::filter.range.to')),
                Html::input()
                    ->setType($this->inputType)
                    ->setName($filterItem->getNamespacedName() . '.' . static::KEY_MAX)
                    ->addAttributes(['step' => $step, 'value' => $this->getRangeValue(static::KEY_MAX)]),
            ])->addClass('column'),
        ])->addClass('range');
    }

    protected function getRangeValue(string $key): ?string
    {
        return Arr::get($this->getValue(), $key);
    }

    /**
     * @param FilterItem $filterItem
     * @param Builder $builder
     * @return void
     */
    public function execute(FilterItem $filterItem, Builder $builder): void
    {
        $min = $this->getRangeValue(static::KEY_MIN);
        $max = $this->getRangeValue(static::KEY_MAX);

        if ($min) {
            $builder->where($filterItem->getName(), '>=', $min);
        }

        if ($max) {
            $builder->where($filterItem->getName(), '<=', $max);
        }
    }

    public function rules(FilterParameters $parameters, callable $attributeResolver): array
    {
        return [
            static::KEY_MIN => ['nullable', 'numeric'],
            static::KEY_MAX => ['nullable', 'numeric'],
        ];
    }

    public function messages(FilterParameters $filterParameters, callable $attributeResolver): array
    {
        return [];
    }

    public function attributes(FilterParameters $filterParameters, callable $attributeResolver): array
    {
        return [];
    }

    /**
     * @param Validator $validator
     * @param FilterParameters $filterParameters
     * @param callable $attributeResolver
     */
    public function withValidator(
        Validator        $validator,
        FilterParameters $filterParameters,
        callable         $attributeResolver
    ): void
    {
        $minAttribute = $attributeResolver(static::KEY_MIN);
        $maxAttribute = $attributeResolver(static::KEY_MAX);

        $validator->sometimes(
            $attributeResolver(static::KEY_MIN),
            "lte:{$maxAttribute}",
            static fn (Fluent $fluent) => ! blank(Arr::get($fluent->getAttributes(), $maxAttribute))
        );

        $validator->sometimes(
            $attributeResolver(static::KEY_MAX),
            "gte:{$minAttribute}",
            static fn (Fluent $fluent) => ! blank(Arr::get($fluent->getAttributes(), $minAttribute))
        );
    }
}
