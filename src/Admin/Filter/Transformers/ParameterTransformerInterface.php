<?php


namespace Arbory\Base\Admin\Filter\Transformers;


use Arbory\Base\Admin\Filter\FilterParameters;

interface ParameterTransformerInterface
{
    public function transform(FilterParameters $parameters, callable $next);
}