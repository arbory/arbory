<?php


namespace Arbory\Base\Admin\Filter;

use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

class FilterValidator
{
    /**
     * @var FilterParameters
     */
    protected $parameters;
    /**
     * @var FilterCollection
     */
    protected $filterCollection;
    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * FilterValidator constructor.
     * @param ValidatorFactory $validatorFactory
     * @param FilterParameters $parameters
     * @param FilterCollection $filterCollection
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        FilterParameters $parameters,
        FilterCollection $filterCollection
    )
    {
        $this->parameters = $parameters;
        $this->filterCollection = $filterCollection;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @return bool
     */
    public function validate(): Boolean
    {
        return $this->getValidator()->fails();
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        $rules = [[]];

        foreach($this->filterCollection as $filterItem)
        {
            $type = $filterItem->getType();

            if(! $type instanceof WithParameterValidation) {
                continue;
            }

            $rules[] = $this->normalizeRules($filterItem, $this->resolveRules($filterItem));
        }

        $rules = array_merge([], ...$rules);

        return $this->validatorFactory->make($this->parameters->toArray(), $rules);
    }

    /**
     * @param FilterItem $filterItem
     * @return Validator
     */
    public function getFilterValidator(FilterItem $filterItem): Validator
    {
        $rules = $this->normalizeRules($filterItem, $this->resolveRules($filterItem));

        return $this->validatorFactory->make($this->parameters, $rules);
    }

    /**
     * @param FilterItem $filterItem
     * @param array $rules
     * @return array
     */
    protected function normalizeRules(FilterItem $filterItem, array $rules): array {
        if(! Arr::isAssoc($rules)) {
            return [
                $filterItem->getName() => $rules
            ];
        }

        $rulesNormalized = [];

        foreach($rules as $field => $ruleList) {
            $rulesNormalized["{$filterItem->getName()}.{$field}"] = $ruleList;
        }

        return $rulesNormalized;
    }

    /**
     * @param FilterItem $filterItem
     * @return mixed
     */
    protected function resolveRules(FilterItem $filterItem)
    {
        $type = $filterItem->getType();

        return $type->rules($this->parameters, function (string $attribute) use ($filterItem) {
            return $filterItem->getName() . "." . $attribute;
        });
    }
}