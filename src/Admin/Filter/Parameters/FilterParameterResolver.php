<?php

namespace Arbory\Base\Admin\Filter\Parameters;

use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Filter\FilterValidatorBuilder;

class FilterParameterResolver
{
    /**
     * ParameterResolver constructor.
     */
    public function __construct(protected ParameterTransformerPipeline $transformerPipeline, protected FilterValidatorBuilder $filterValidator)
    {
    }

    public function resolve(FilterManager $filterManager): FilterParameters
    {
        $this->transformerPipeline->setParameters($this->createParameters());
        $this->transformerPipeline->setTransformers($filterManager->getTransformers());

        $parameters = $this->transformerPipeline->execute();

        return $this->filterInvalidParameterValues($parameters, $filterManager);
    }

    protected function filterInvalidParameterValues(
        FilterParameters $parameters,
        FilterManager $filterManager
    ): FilterParameters {
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

    protected function createParameters(): FilterParameters
    {
        return new FilterParameters();
    }
}
