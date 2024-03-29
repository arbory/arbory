<?php

namespace Arbory\Base\Links;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property bool $new_tab
 * @property string $href
 */
class Link extends Model
{
    use HasFactory;

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
    public function __toString()
    {
        return (string) $this->href;
    }

    /**
     * @return bool
     */
    public function isNewTab(): bool
    {
        return (bool) $this->new_tab;
    }
}
