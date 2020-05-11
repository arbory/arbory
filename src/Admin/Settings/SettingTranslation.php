<?php

namespace Arbory\Base\Admin\Settings;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'setting_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'setting_name', 'value', 'locale',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_name');
    }
}
