<?php

namespace Arbory\Base\Admin\Navigator;

interface NavigableInterface
{
    /**
     * @return bool
     */
    public function isNavigable(): bool;

    /**
     * @param  Navigator  $navigator
     *
     * @return mixed
     */
    public function navigator(Navigator $navigator);
}
