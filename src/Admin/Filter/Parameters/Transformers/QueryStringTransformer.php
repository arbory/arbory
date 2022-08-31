<?php

namespace Arbory\Base\Admin\Filter\Parameters\Transformers;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

class QueryStringTransformer implements ParameterTransformerInterface
{
    /**
     * QueryStringTransformer constructor.
     */
    public function __construct(private Request $request)
    {
    }

    /**
     * @return mixed
     */
    public function transform(FilterParameters $parameters, callable $next)
    {
        $parameters->add(
            (array) $this->request->input(
                $parameters->getNamespace()
            )
        );

        return $next($parameters);
    }

    public function stringify(FilterParameters $parameters): ?string
    {
        return http_build_query($parameters->toArray());
    }
}
