<?php

namespace Arbory\Base\Support\Activation;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasActivationDates
{

    /**
     * @param $value
     * @return Carbon|null
     */
    public function getActivateAtAttribute($value)
    {
        return is_null($value) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $value);
    }

    /**
     * @param $value
     * @return Carbon|null
     */
    public function getExpireAtAttribute($value)
    {
        return is_null($value) ? null : Carbon::createFromFormat('Y-m-d H:i:s', $value);
    }

    /**
     * @return bool
     */
    public function getActiveAttribute()
    {
        if (is_null($this->activate_at)
            || $this->activate_at->isFuture()
            || (!is_null($this->expire_at) && $this->expire_at->isPast())) {
            return false;
        }

        return true;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        $table = $this->getTable();
        $now   = date('Y-m-d H:i:s');

        return $query->where($table . '.activate_at', '<=', $now)
            ->where(function (Builder $query) use ($table, $now)
            {
                return $query->where($table . '.expire_at', '>=', $now)
                    ->orWhereNull($table . '.expire_at');
            });
    }

}
