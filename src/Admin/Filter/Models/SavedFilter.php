<?php

namespace Arbory\Base\Admin\Filter\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SavedFilter.
 *
 * @property int $id
 * @property string $name
 * @property string $module
 * @property string $filter
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter whereFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SavedFilter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SavedFilter extends Model
{
    /**
     * @var string
     */
    protected $table = 'admin_saved_filters';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'module',
        'filter',
    ];
}
