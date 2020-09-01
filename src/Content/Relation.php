<?php

namespace Arbory\Base\Content;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
        'owner_type',
        'related_id',
        'related_type',
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

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
    public function related()
    {
        return $this->morphTo();
    }
}
