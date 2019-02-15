<?php


namespace Arbory\Base\Admin\Layout;


use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Html;
use Closure;

class BreadcrumbsLayout implements LayoutInterface
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    public function use(LayoutInterface $layout)
    {
        // TODO: Implement use() method.
    }

    public function apply(Content $content, Closure $next, ...$parameters)
    {
        $content->prepend($this->render());

        return $next($content);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return Html::header($this->breadcrumbs->render());
    }

    /**
     * @param mixed $breadcrumbs
     *
     * @return BreadcrumbsLayout
     */
    public function setBreadcrumbs($breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        return $this;
    }
}