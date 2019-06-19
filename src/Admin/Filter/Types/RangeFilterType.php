<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterParameters;
use Arbory\Base\Admin\Filter\FilterTypeInterface;
use Arbory\Base\Admin\Filter\Transformers;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class RangeFilterType extends AbstractType implements FilterTypeInterface, WithCustomExecutor, WithParameterValidation
{
    const KEY_MIN = 'min';
    const KEY_MAX = 'max';

    protected $inputType = 'number';

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem)
    {
        $step = $this->configuration['step'] ?? '.01';

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

    /**
     * @param string $key
     * @return string|null
     */
    protected function getRangeValue(string $key): ?string
    {
        return Arr::get($this->getValue(), $key);
    }

    /**
     * @param FilterItem $filterItem
     * @param Builder $builder
     * @return mixed
     */
    public function execute(FilterItem $filterItem, Builder $builder)
    {
        $min = $this->getRangeValue(static::KEY_MIN);
        $max = $this->getRangeValue(static::KEY_MAX);

        if($min) {
            $builder->where($filterItem->getName(), '>=', $min);
        }

        if($max) {
            $builder->where($filterItem->getName(), '<', $max);
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
            static::KEY_MIN => ['nullable', 'numeric', "lt:{$maxAttribute}"],
            static::KEY_MAX => ['nullable', 'numeric', "gt:{$minAttribute}"]
        ];
    }
}