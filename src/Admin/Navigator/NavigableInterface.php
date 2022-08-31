<?php

namespace Arbory\Base\Admin\Navigator;

interface NavigableInterface
{
    /**
     * @return bool
     */
    public function isNavigable(): bool;

    /**
     * @return mixed
     */
    public function navigator(Navigator $navigator);
}
