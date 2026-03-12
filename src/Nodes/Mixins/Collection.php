<?php

namespace Arbory\Base\Nodes\Mixins;

use Closure;

/**
 * Class Collection.
 *
 * @mixin \Illuminate\Support\Collection
 */
class Collection
{
    public function unorderedHierarchicalList(): Closure
    {
        return function () {
            return $this->toHierarchy();
        };
    }
}
