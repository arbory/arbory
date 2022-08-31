<?php

namespace Arbory\Base\Admin\Navigator;

interface NavigableInterface
{
    public function isNavigable(): bool;

    /**
     * @return mixed
     */
    public function navigator(Navigator $navigator);
}
