<?php

namespace Arbory\Base\Admin\Constructor\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;

class ConstructorPage extends Model
{
    /**
     * @var string
     */
    protected $table = 'constructor_pages';

    /**
     * @return MorphMany
     */
    public function blocks()
    {
        return $this->morphMany(ConstructorBlock::class, 'owner')->orderBy('position');
    }
}
