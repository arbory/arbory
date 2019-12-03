<?php


namespace Arbory\Base\Nodes\Mixins;


use Closure;

/**
 * Class Collection
 * @package Arbory\Base\Nodes\Mixins
 *
 * @mixin \Illuminate\Support\Collection
 * @mixin \Baum\Mixins\Collection
 */
class Collection
{
    /**
     * @return Closure
     */
    public function unorderedHierarchicalList()
    {
        return function () {
            return $this->toHierarchy();
        };
    }
}