<?php

namespace Arbory\Base\Admin\Filter\Concerns;

use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

interface WithParameterValidation
{
    /**
     * @param FilterParameters $parameters
     * @param callable $attributeResolver
     *
     * @return array
     */
    public function rules(FilterParameters $parameters, callable $attributeResolver): array;

    /**
     * @param FilterParameters $parameters
     * @param callable $attributeResolver
     *
     * @return array
     */
    public function messages(FilterParameters $parameters, callable $attributeResolver): array;

    /**
     * @param FilterParameters $parameters
     * @param callable $attributeResolver
     *
     * @return array
     */
    public function attributes(FilterParameters $parameters, callable $attributeResolver): array;
}
