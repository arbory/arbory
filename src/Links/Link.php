<?php

namespace CubeSystems\Leaf\Links;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'new_tab',
        'href',
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->href;
    }
}