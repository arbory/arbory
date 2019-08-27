<?php

namespace Arbory\Base\Admin\Filter\Parameters\Transformers;

use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

interface ParameterTransformerInterface
{
    /**
     * @param FilterParameters $parameters
     * @param callable $next
     * @return mixed
     */
    public function transform(FilterParameters $parameters, callable $next);

    /**
     * @param FilterParameters $parameters
     * @return string|null
     */
    public function stringify(FilterParameters $parameters): ?string;
}
