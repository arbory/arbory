<?php

namespace Arbory\Base\Admin\Filter\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SavedFilter.
 *
 * @property int $id
 * @property string $name
 * @property string $module
 * @property string $filter
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|SavedFilter newModelQuery()
 * @method static Builder|SavedFilter newQuery()
 * @method static Builder|SavedFilter query()
 * @method static Builder|SavedFilter whereName($value)
 * @method static Builder|SavedFilter whereModule($value)
 * @method static Builder|SavedFilter whereFilter($value)
 * @method static Builder|SavedFilter whereCreatedAt($value)
 * @method static Builder|SavedFilter whereUpdatedAt($value)
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
