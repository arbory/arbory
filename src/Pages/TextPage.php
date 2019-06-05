<?php

namespace Arbory\Base\Pages;

use Illuminate\Database\Eloquent\Model;

class TextPage extends Model
{
    /**
     * @var string
     */
    protected $table = 'text_pages';

    /**
     * @var array
     */
    protected $fillable = [
        'html',
    ];
}
