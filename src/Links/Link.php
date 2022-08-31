<?php

namespace Arbory\Base\Links;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'new_tab',
        'href',
    ];

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->href;
    }

    public function isNewTab(): bool
    {
        return (bool)$this->new_tab;
    }
}
