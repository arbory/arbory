<?php

namespace CubeSystems\Leaf\Pages;

use Illuminate\Database\Eloquent\Model;

class LeafFilePage extends Model implements PageInterface
{
    /**
     * @var array
     */
    protected $fillable = [ 'leaf_file' ];
}