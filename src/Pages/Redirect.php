<?php

namespace Arbory\Base\Pages;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    public const AVAILABLE_STATUSES = [
        301,
        302
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'from_url',
        'to_url',
        'status'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->to_url;
    }
}
