<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Closure;
use Illuminate\Contracts\Support\Renderable;

interface LayoutInterface extends Renderable
{
    public function use(LayoutInterface $layout);

    public function apply(Content $content, Closure $next, ...$parameters);
}