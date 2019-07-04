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
    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * FilterValidator constructor.
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }


    /**
     * @param FilterCollection $filterCollection
     * @param FilterParameters $filterParameters
     *
     * @return bool
     */
    public function validate(FilterCollection $filterCollection, FilterParameters $filterParameters): bool
    {
        return $this->build($filterCollection, $filterParameters)->fails();
    }


    /**
     * @param FilterCollection $filterCollection
     * @param FilterParameters $filterParameters
     * @return Validator
     */
    public function build(FilterCollection $filterCollection, FilterParameters $filterParameters): Validator
    {
        $rules = [[]];
        $attributes = [[]];
        $messages = [[]];
        $transformers = [];

        foreach ($filterCollection->findByConcerns([WithParameterValidation::class]) as $filterItem) {
            $type = $filterItem->getType();
            $data = $this->buildForFilter($filterItem, $filterParameters);

            $rules[] = $data[0];
            $messages[] = $data[1];
            $attributes[] = $data[2];

            if(method_exists($type, 'withValidator')) {
                $transformers[] = [Closure::fromCallable([$type, 'withValidator']), $this->getAttributeResolver($filterItem)];
            }
        }

        $validator = $this->validatorFactory->make(
            $filterParameters->toArray(),
            array_merge(...$rules),
            array_merge(...$messages),
            array_merge(...$attributes)
        );

        foreach($transformers as $transformerData) {
            $transformer = $transformerData[0];

            $transformer($validator, $filterParameters, $transformerData[1]);
        }

        return $validator;
    }

    /**
     * @param FilterItem $filterItem
     * @param FilterParameters $filterParameters
     *
     * @return array
     */
    public function buildForFilter(FilterItem $filterItem, FilterParameters $filterParameters): array
    {
        $rules = $this->normalize($filterItem, $this->resolveMethod('rules', $filterItem, $filterParameters));
        $messages = $this->normalize($filterItem, $this->resolveMethod('messages', $filterItem, $filterParameters), false);
        $attributes = $this->normalize($filterItem, $this->resolveMethod('attributes', $filterItem, $filterParameters), false);

        return [
            $rules,
            $messages,
            $attributes,
        ];
    }

    /**
     * @param FilterItem $filterItem
     * @param array $data
     * @param bool $prependName
     *
     * @return array
     */
    protected function normalize(FilterItem $filterItem, array $data, bool $prependName = true): array
    {
        // A single field with rules
        if (! Arr::isAssoc($data)) {
            return [
                $filterItem->getName() => $data
            ];
        }

        $normalized = [];

        foreach ($data as $field => $ruleList) {
            $name = $field;

            if($prependName) {
                $name = "{$filterItem->getName()}.{$name}";
            }

            $normalized[$name] = $ruleList;
        }

        return $normalized;
    }

    /**
     * @param string $method
     * @param FilterItem $filterItem
     * @param FilterParameters $filterParameters
     * @return mixed
     */
    protected function resolveMethod(string $method, FilterItem $filterItem, FilterParameters $filterParameters)
    {
        /**
         * @var WithParameterValidation $type
         */
        $type = $filterItem->getType();

        return $type->{$method}($filterParameters, $this->getAttributeResolver($filterItem));
    }

    /**
     * @param FilterItem $filterItem
     * @return callable
     */
    protected function getAttributeResolver(FilterItem $filterItem): callable
    {
        return static function(?string $attribute = null) use($filterItem) {
            return $filterItem->getName() . ($attribute ? '.' . $attribute : '');
        };
    }
}