<?php

namespace CubeSystems\Leaf\Links;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'title',
        'new_tab',
        'href',
    ];
}