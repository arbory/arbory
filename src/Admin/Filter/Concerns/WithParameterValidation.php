<?php

namespace Arbory\Base\Admin\Filter\Concerns;

use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

interface WithParameterValidation
{
    public function rules(FilterParameters $parameters, callable $attributeResolver): array;

    public function messages(FilterParameters $parameters, callable $attributeResolver): array;

    public function attributes(FilterParameters $parameters, callable $attributeResolver): array;
}
