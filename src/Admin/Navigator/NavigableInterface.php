<?php


namespace Arbory\Base\Admin\Navigator;


interface NavigableInterface
{
    public function isNavigable():bool;
    public function navigator(Navigator $navigator);
}