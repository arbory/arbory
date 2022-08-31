<?php

namespace Arbory\Base\Admin\Filter\Parameters\Transformers;

use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

/**
 * Class DefaultValueTransformer.
 */
class DefaultValueTransformer implements ParameterTransformerInterface
{
    /**
     * DefaultValueTransformer constructor.
     */
    public function __construct(private FilterManager $filterManager)
    {
    }

    /**
     * @return mixed
     */
    public function transform(FilterParameters $parameters, callable $next)
    {
        if (request()->has($parameters->getNamespace())) {
            return $next($parameters);
        }

        foreach ($this->filterManager->getFilters() as $filterItem) {
            if (! empty($filterItem->getDefaultValue())) {
                $parameters->add([$filterItem->getName() => $filterItem->getDefaultValue()]);
            }
        }

        return $next($parameters);
    }

    public function stringify(FilterParameters $parameters): ?string
    {
        return null;
    }
}
