<?php

namespace Arbory\Base\Admin\Filter\Parameters\Transformers;

use Arbory\Base\Admin\Filter\FilterManager;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

/**
 * Class DefaultValueTransformer.
 */
class DefaultValueTransformer implements ParameterTransformerInterface
{
    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * DefaultValueTransformer constructor.
     * @param FilterManager $filterManager
     */
    public function __construct(FilterManager $filterManager)
    {
        $this->filterManager = $filterManager;
    }

    /**
     * @param FilterParameters $parameters
     * @param callable $next
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

    /**
     * @param FilterParameters $parameters
     * @return string|null
     */
    public function stringify(FilterParameters $parameters): ?string
    {
        return null;
    }
}
