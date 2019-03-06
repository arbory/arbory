<?php


namespace Arbory\Base\Admin\Constructor\Models;


use Illuminate\Database\Eloquent\Model;

class ConstructorPage extends Model
{
    protected $table = 'constructor_pages';

    public function blocks()
    {
        return $this->morphMany(ConstructorBlock::class, 'owner');
    }
}