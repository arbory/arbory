<?php

namespace Arbory\Base\Pages;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'from_url',
        'to_url'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->to_url;
    }
}
