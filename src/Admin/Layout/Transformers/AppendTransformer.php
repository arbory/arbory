<?php

namespace Arbory\Base\Admin\Layout\Transformers;

use Closure;
use Arbory\Base\Admin\Layout\Body;

class AppendTransformer
{
    /**
     * AppendTransformer constructor.
     */
    public function __construct(protected mixed $content)
    {
    }

    public function __invoke(Body $body, $next)
    {
        $body->append(value($this->content));

        return $next($body);
    }
}
