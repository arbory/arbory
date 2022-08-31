<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Admin\Widgets\Breadcrumbs;

interface PageInterface extends LayoutInterface
{
    /**
     * @param $class
     * @return mixed
     */
    public function bodyClass($class);

    /**
     * @param  string  $title
     * @param  string  $url
     * @return mixed
     */
    public function addBreadcrumb($title, $url);

    public function setBreadcrumbs(?Breadcrumbs $breadcrumbs): self;

    public function getBreadcrumbs(): ?Breadcrumbs;
}
