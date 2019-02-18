<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;
use Closure;

interface TransformableInterface
{
    /**
     * @param Wrappable $content
     * @param Closure   $next
     * @param array     ...$parameters
     *
     * @return mixed
     */
    public function apply($content, Closure $next, array ...$parameters);
}