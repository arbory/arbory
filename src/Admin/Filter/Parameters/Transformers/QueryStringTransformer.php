<?php

namespace Arbory\Base\Admin\Filter\Parameters\Transformers;

use Illuminate\Http\Request;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;

class QueryStringTransformer implements ParameterTransformerInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * QueryStringTransformer constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param FilterParameters $parameters
     * @param callable $next
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

    /**
     * @param FilterParameters $parameters
     * @return string|null
     */
    public function stringify(FilterParameters $parameters): ?string
    {
        return http_build_query($parameters->toArray());
    }
}
