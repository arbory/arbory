<?php

namespace CubeSystems\Leaf\Admin\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * @var bool
     */
    public $incrementing  = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'type'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }
}