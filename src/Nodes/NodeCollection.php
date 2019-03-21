<?php

namespace Arbory\Base\Nodes;

class NodeCollection extends \Baum\Extensions\Eloquent\Collection
{
    public function unorderedHierarchicalList()
    {
        $dict = $this->getDictionary();

        return new static($this->hierarchical($dict));
    }
}
