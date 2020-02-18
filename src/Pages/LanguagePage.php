<?php

namespace Arbory\Base\Pages;

use Carbon\Carbon;
use Arbory\Base\Support\Translate\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LanguagePage.
 *
 * @property int $id
 * @property int $language_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Language $language
 * @method static Builder|LanguagePage whereCreatedAt($value)
 * @method static Builder|LanguagePage whereId($value)
 * @method static Builder|LanguagePage whereLanguageId($value)
 * @method static Builder|LanguagePage whereUpdatedAt($value)
 * @method static Builder|LanguagePage newModelQuery()
 * @method static Builder|LanguagePage newQuery()
 * @method static Builder|LanguagePage query()
 * @mixin \Eloquent
 */
class LanguagePage extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'language_id',
    ];

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->language->getAttribute('locale');
    }
}
