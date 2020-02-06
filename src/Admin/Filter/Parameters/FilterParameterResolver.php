<?php

namespace Arbory\Base\Admin\Filter\Parameters;

use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Filter\FilterValidatorBuilder;

class FilterParameterResolver
{
    /**
     * @var ParameterTransformerPipeline
     */
    protected $transformerPipeline;
    /**
     * @var FilterValidatorBuilder
     */
    protected $filterValidator;

    /**
     * ParameterResolver constructor.
     * @param ParameterTransformerPipeline $transformerPipeline
     * @param FilterValidatorBuilder $filterValidator
     */
    public function __construct(ParameterTransformerPipeline $transformerPipeline, FilterValidatorBuilder $filterValidator)
    {
        $this->transformerPipeline = $transformerPipeline;
        $this->filterValidator = $filterValidator;
    }

    /**
     * @param FilterManager $filterManager
     * @return FilterParameters
     */
    public function resolve(FilterManager $filterManager): FilterParameters
    {
        $this->transformerPipeline->setParameters($this->createParameters());
        $this->transformerPipeline->setTransformers($filterManager->getTransformers());

        $parameters = $this->transformerPipeline->execute();

        return $this->filterInvalidParameterValues($parameters, $filterManager);
    }

    /**
     * @param FilterParameters $parameters
     * @param FilterManager $filterManager
     *
     * @return FilterParameters
     */
    protected function filterInvalidParameterValues(FilterParameters $parameters, FilterManager $filterManager): FilterParameters
    {
        $filterCollection = $filterManager->getFilters();
        $errors = $this->filterValidator->build($filterCollection, $parameters)->getMessageBag();

        foreach ($filterCollection as $filterItem) {
            $value = $parameters->getFromFilter($filterItem);
            $name = $filterItem->getName();
            $errorWildcard = "{$name}.*";

            if ($errors->has($errorWildcard)) {
                $value = $filterItem->getDefaultValue();

                $parameters->set($name, $value);
                $parameters->addErrors($name, $errors->get($errorWildcard));
            }

            $filterItem->getType()->setValue(
                $value
            );
        }

        return $parameters;
    }

    /**
     * @return FilterParameters
     */
    protected function createParameters(): FilterParameters
    {
        return new FilterParameters();
    }
}
