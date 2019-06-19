<?php


namespace Arbory\Base\Admin\Filter\Concerns;

use Arbory\Base\Admin\Filter\FilterParameters;

interface WithParameterValidation
{
    /**
     * @param FilterParameters $parameters
     * @param callable $attributeResolver
     * @return array
     */
    public function rules(FilterParameters $parameters, callable $attributeResolver): array;
}