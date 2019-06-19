<?php


namespace Arbory\Base\Admin\Filter\Transformers;


use Arbory\Base\Admin\Filter\FilterParameters;
use Illuminate\Http\Request;

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
}