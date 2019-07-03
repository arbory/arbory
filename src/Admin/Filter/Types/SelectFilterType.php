<?php


namespace Arbory\Base\Admin\Filter\Types;


use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterTypeInterface;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Arbory\Base\Admin\Form\Controls\SelectControl;
use Arbory\Base\Html\Html;
use Illuminate\Validation\Rule;

class SelectFilterType extends AbstractType implements FilterTypeInterface, WithParameterValidation
{
    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    public function render(FilterItem $filterItem)
    {
        $options = $this->config['options'] ?? [];
        $multiple = $this->config['multiple'] ?? false;

        $control = new SelectControl();
        $control->setName($control->getInputName($filterItem->getNamespacedName()));
        $control->setOptions($options);
        $control->setMultiple($multiple);
        $control->setSelected($this->getValue());

        return Html::div($control->render($control->element()))->addClass('select');
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
        return [
            'nullable',
            Rule::in(array_keys($this->config['options'] ?? []))
        ];
    }

    /**
     * @param FilterParameters $filterParameters
     * @param callable $attributeResolver
     *
     * @return array
     */
    public function messages(FilterParameters $filterParameters, callable $attributeResolver): array
    {
        return [];
    }

    /**
     * @param FilterParameters $filterParameters
     * @param callable $attributeResolver
     *
     * @return array
     */
    public function attributes(FilterParameters $filterParameters, callable $attributeResolver): array
    {
        return [];
    }
}