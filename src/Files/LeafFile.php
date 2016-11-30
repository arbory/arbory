<?php

namespace CubeSystems\Leaf\Files;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string original_name
 * @property string disk
 * @property string sha1
 * @property int size
 * @property int|string owner_id
 * @property string owner_class
 * @property string local_name
 */
class LeafFile extends Model
{
    use UuidModelTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'disk',
        'original_name',
        'local_name',
        'sha1',
        'size'
    ];

    /**
     * @var
     */
    protected $updateFile;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->original_name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getSha1()
    {
        return $this->sha1;
    }

    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getLocalName()
    {
        return $this->local_name;
    }
}
