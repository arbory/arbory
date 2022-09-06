<?php

namespace Arbory\Base\Admin\Filter;

use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

class FilterValidatorBuilder
{
    protected const VALIDATION_CONCERNS = [WithParameterValidation::class];

    /**
     * FilterValidator constructor.
     */
    public function __construct(protected ValidatorFactory $validatorFactory)
    {
    }

    public function validate(FilterCollection $filterCollection, FilterParameters $filterParameters): bool
    {
        return $this->build($filterCollection, $filterParameters)->fails();
    }

    public function build(FilterCollection $filterCollection, FilterParameters $filterParameters): Validator
    {
        $validationObject = new FilterValidationObject();

        foreach ($filterCollection->findByConcerns(self::VALIDATION_CONCERNS) as $filterItem) {
            $this->buildFilterItem($validationObject, $filterParameters, $filterItem);
        }

        $validator = $this->validatorFactory->make(
            $filterParameters->toArray(),
            $validationObject->getRules(),
            $validationObject->getMessages(),
            $validationObject->getAttributes()
        );

        $this->applyTransformers($validationObject->getTransformers(), $validator, $filterParameters);

        return $validator;
    }

    protected function buildFilterItem(
        FilterValidationObject $validationObject,
        FilterParameters $filterParameters,
        FilterItem $filterItem
    ): void {
        $type = $filterItem->getType();

        $validationObject->addRules($this->buildRules($filterItem, $filterParameters));
        $validationObject->addMessages($this->buildMessages($filterItem, $filterParameters));
        $validationObject->addAttributes($this->buildAttributes($filterItem, $filterParameters));

        if (method_exists($type, 'withValidator')) {
            $validationObject->addTransformers([
                Closure::fromCallable([$type, 'withValidator']),
                $this->getAttributeResolver($filterItem),
            ]);
        }
    }

    /**
     * @param $validator
     */
    protected function applyTransformers(array $transformers, $validator, FilterParameters $filterParameters): void
    {
        foreach ($transformers as $transformerData) {
            $transformer = $transformerData[0];

            $transformer($validator, $filterParameters, $transformerData[1]);
        }
    }

    public function buildRules(FilterItem $filterItem, FilterParameters $filterParameters): array
    {
        return $this->normalize($filterItem, $this->resolveMethod('rules', $filterItem, $filterParameters));
    }

    public function buildMessages(FilterItem $filterItem, FilterParameters $filterParameters): array
    {
        return $this->normalize($filterItem, $this->resolveMethod('messages', $filterItem, $filterParameters), false);
    }

    public function buildAttributes(FilterItem $filterItem, FilterParameters $filterParameters): array
    {
        return $this->normalize($filterItem, $this->resolveMethod('attributes', $filterItem, $filterParameters), false);
    }

    protected function normalize(FilterItem $filterItem, array $data, bool $prependName = true): array
    {
        // A single field with rules
        if (! Arr::isAssoc($data)) {
            return [
                $filterItem->getName() => $data,
            ];
        }

        $normalized = [];

        foreach ($data as $field => $ruleList) {
            $name = $field;

            if ($prependName) {
                $name = "{$filterItem->getName()}.{$name}";
            }

            $normalized[$name] = $ruleList;
        }

        return $normalized;
    }

    /**
     * @return mixed
     */
    protected function resolveMethod(string $method, FilterItem $filterItem, FilterParameters $filterParameters)
    {
        /**
         * @var WithParameterValidation
         */
        $type = $filterItem->getType();

        return $type->{$method}($filterParameters, $this->getAttributeResolver($filterItem));
    }

    protected function getAttributeResolver(FilterItem $filterItem): callable
    {
        return static fn (?string $attribute = null) => $filterItem->getName() . ($attribute ? '.' . $attribute : '');
    }
}
