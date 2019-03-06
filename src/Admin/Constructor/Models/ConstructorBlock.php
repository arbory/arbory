<?php

namespace Arbory\Base\Admin\Constructor\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructorBlock extends Model
{
    protected $table = 'constructor_blocks';

    protected $fillable = [
        'name',
        'content_type',
        'content_id',
        'position'
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    public function content()
    {
        return $this->morphTo();
    }
}
