<?php

namespace Arbory\Base\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $name
 * @property int|string $owner_id
 * @property string $owner_type
 * @property int|string $related_id
 * @property string $related_type
 */
class Relation extends Model
{
    use HasFactory;

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
        return (string)$this->name;
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}
