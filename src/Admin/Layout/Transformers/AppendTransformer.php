<?php

namespace Arbory\Base\Admin\Layout\Transformers;

use Closure;
use Arbory\Base\Admin\Layout\Body;

class AppendTransformer
{
    /**
     * AppendTransformer constructor.
     *
     * @param string|Closure $content
     */
    public function __construct(protected $content)
    {
    }

    public function __invoke(Body $body, $next)
    {
        $body->append(value($this->content));

        return $next($body);
    }
}
