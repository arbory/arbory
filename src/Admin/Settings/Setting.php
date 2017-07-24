<?php

namespace CubeSystems\Leaf\Admin\Settings;

use CubeSystems\Leaf\Files\LeafFile;
use CubeSystems\Leaf\Support\Translate\Translatable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Translatable;

    /**
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * @var string
     */
    protected $translationForeignKey = 'setting_name';

    /**
     * @var bool
     */
    public $incrementing  = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'type'
    ];

    /**
     * @var array
     */
    protected $translatedAttributes = [
        'value'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param mixed $column
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function value( $column = null )
    {
        return $column ? parent::value( $column ) : $this->belongsTo( LeafFile::class, 'value' );
    }
}