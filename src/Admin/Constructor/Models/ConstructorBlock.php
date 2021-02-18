<?php

namespace Arbory\Base\Admin\Constructor\Models;

use Illuminate\Database\Eloquent\Model;

class ConstructorBlock extends Model
{
    /**
     * @var string
     */
    protected $table = 'constructor_blocks';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'content_type',
        'content_id',
        'position',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function content()
    {
        return $this->morphTo();
    }
}
