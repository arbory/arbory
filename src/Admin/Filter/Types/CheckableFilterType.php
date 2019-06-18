<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterTypeInterface;
use Arbory\Base\Html\Html;

class CheckableFilterType extends AbstractType implements FilterTypeInterface
{
    protected $value;

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem)
    {
        $options = $this->configuration['options'] ?? [];

        $labels = [];

        foreach($options as $key => $value) {
            $checked = in_array($key, (array) $this->getValue());

            $labels[] = Html::label([
                Html::input((string) $value)
                    ->setType('checkbox')
                    ->addAttributes(['value' => $key])
                    ->addAttributes($checked ? ['checked' => true] : [])
                    ->setName($filterItem->getNamespacedName() . '[]'),
            ]);
        }

        return Html::div($labels)->addClass('checkbox');
    }
}