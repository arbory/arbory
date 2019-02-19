<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;
use Closure;

interface TransformableInterface
{
    /**
     * @param Body    $content
     * @param Closure $next
     * @param array   ...$parameters
     *
     * @return mixed
     */
    public function apply(Body $content, Closure $next, array ...$parameters);
}