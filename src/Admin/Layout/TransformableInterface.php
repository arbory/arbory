<?php

namespace Arbory\Base\Admin\Layout;

use Closure;
use Arbory\Base\Html\Elements\Content;

interface TransformableInterface
{
    /**
     * Transforms content.
     *
     * @param Body $content
     * @param Closure $next
     * @param array ...$parameters
     *
     * @return mixed
     */
    public function apply(Body $content, Closure $next, array ...$parameters);
}
