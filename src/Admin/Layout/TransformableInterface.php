<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Content;
use Closure;

interface TransformableInterface
{
    /**
     * @param LayoutContent $content
     * @param Closure       $next
     * @param array         ...$parameters
     *
     * @return mixed
     */
    public function apply(LayoutContent $content, Closure $next, array ...$parameters);
}