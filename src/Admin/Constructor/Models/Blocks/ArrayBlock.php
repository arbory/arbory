<?php

namespace Arbory\Base\Admin\Constructor\Models\Blocks;

use Illuminate\Database\Eloquent\Model;

class ArrayBlock extends Model
{
    protected $casts = [
        'data' => 'array',
    ];
}
