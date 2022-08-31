<?php

namespace Arbory\Base\Admin\Layout;

use Closure;
use Arbory\Base\Html\Elements\Content;

interface TransformableInterface
{
    /**
     * Transforms content.
     *
     * @return mixed
     */
    public function apply(Body $content, Closure $next, array ...$parameters);
}
