<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\Concerns\WithCustomExecutor;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterTypeInterface;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Builder;

class TextFilterType extends AbstractType implements FilterTypeInterface, WithCustomExecutor
{
    public const BEGINS_WITH = 'begins';
    public const ENDS_WITH = 'ends';
    public const CONTAINS = 'contains';
    public const EQUALS = 'equals';

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem)
    {
        return Html::div(
            Html::input()
                ->setName($filterItem->getNamespacedName())
                ->setValue($this->getValue())
                ->addClass('text')
        )->addClass('text');
    }

    /**
     * @param FilterItem $filterItem
     * @param Builder $builder
     */
    public function execute(FilterItem $filterItem, Builder $builder): void
    {
        $type = $this->config['type'] ?? static::CONTAINS;

        $resolvedValue = $this->resolveLikeQuery($type, $this->value);
        $operator = $resolvedValue === $this->value ? '=' : 'LIKE';

        $builder->where($filterItem->getName(), $operator, $resolvedValue);
    }

    /**
     * @param string $type
     * @param string $value
     * @return string
     */
    protected function resolveLikeQuery(string $type, string $value): string
    {
        if($type === static::BEGINS_WITH) {
            return "%{$value}";
        }

        if($type === static::ENDS_WITH) {
            return "{$value}%";
        }

        if($type === static::CONTAINS) {
            return "%{$value}%";
        }

        return $value;
    }
}