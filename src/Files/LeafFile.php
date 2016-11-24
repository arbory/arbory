<?php

namespace CubeSystems\Leaf\Files;


use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

class LeafFile extends Model
{
    use UuidModelTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'disk',
        'original_name',
        'sha1',
        'size'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->original_name;
    }
}
