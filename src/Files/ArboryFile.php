<?php

namespace Arbory\Base\Files;

use Database\Factories\Files\ArboryFileModelFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * Class ArboryFile.
 *
 * @property string $id
 * @property string $original_name
 * @property string $disk
 * @property string $sha1
 * @property int $size
 * @property int|string $owner_id
 * @property string $owner_class
 * @property string $local_name
 */
class ArboryFile extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    public $keyType = 'string';

    /**
     * @var string
     */
    protected $table = 'files';

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
        'size',
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
    public function getUrl()
    {
        return Storage::disk($this->getDisk())->url($this->getLocalName());
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->getOriginalName(), PATHINFO_EXTENSION);
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
     * @return string
     */
    public function getDisk()
    {
        return $this->disk;
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

    /**
     * @return MorphTo
     */
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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ArboryFileModelFactory::new();
    }
}
