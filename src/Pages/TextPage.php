<?php

namespace CubeSystems\Leaf\Pages;

use Illuminate\Database\Eloquent\Model;

class TextPage extends Model implements PageInterface
{
    protected $fillable = [ 'html' ];
}
