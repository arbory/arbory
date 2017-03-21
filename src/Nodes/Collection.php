<?php

namespace CubeSystems\Leaf\Nodes;

class Collection extends \Baum\Extensions\Eloquent\Collection
{
    public function unorderedHierarchicalList()
    {
        $dict = $this->getDictionary();

        return new static( $this->hierarchical( $dict ) );
    }
}
