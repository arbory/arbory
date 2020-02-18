<?php

namespace Arbory\Base\Nodes;

use Arbory\Base\Support\Translate\Language;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LanguageLinkedNode.
 *
 * @property int $id
 * @property int $link
 * @property int|null $language_id
 * @property string|null $node_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Language|null $language
 * @property-read Node|null $node
 * @method static Builder|LanguageLinkedNode newModelQuery()
 * @method static Builder|LanguageLinkedNode newQuery()
 * @method static Builder|LanguageLinkedNode query()
 * @method static Builder|LanguageLinkedNode whereCreatedAt($value)
 * @method static Builder|LanguageLinkedNode whereId($value)
 * @method static Builder|LanguageLinkedNode whereLink($value)
 * @method static Builder|LanguageLinkedNode whereLanguageId($value)
 * @method static Builder|LanguageLinkedNode whereNodeId($value)
 * @method static Builder|LanguageLinkedNode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageLinkedNode extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'link',
        'language_id',
        'node_id',
    ];

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}
