<?php

namespace CubeSystems\Leaf;

class Node extends \Baum\Node
{
    protected $fillable = [
        'name',
        'slug',
        'content_type',
        'content_id',
        'item_position',
        'active',
        'locale',
    ];

    public function __toString()
    {
        return (string) $this->name;
    }

    public function content()
    {
        return $this->morphTo();
    }


}
