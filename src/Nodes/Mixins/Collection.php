<?php

namespace Arbory\Base\Nodes\Mixins;

use Closure;

/**
 * Class Collection.
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
        return fn() => $this->toHierarchy();
    }
}
