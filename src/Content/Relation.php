<?php

namespace Arbory\Base\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * @return MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function related()
    {
        return $this->morphTo();
    }
}
