<?php

namespace Arbory\Base\Admin\Layout\Transformers;

use Arbory\Base\Admin\Layout\Body;

class AppendTransformer
{
    protected $content;

    /**
     * AppendTransformer constructor.
     *
     * @param string|\Closure $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function __invoke(Body $body, $next)
    {
        $body->append(value($this->content));

        return $next($body);
    }
}
