<?php


namespace Arbory\Base\Admin\Filter\Concerns;


use Arbory\Base\Admin\Filter\Parameters;

interface WithParameterValidation
{
    /**
     * @param Parameters $parameters
     * @param callable $attributeResolver
     * @return array
     */
    public function rules(Parameters $parameters, callable $attributeResolver): array;
}