<?php

namespace Arbory\Base\Nodes;

use Arbory\Base\Pages\PageInterface;
use Arbory\Base\Services\NodeRoutesCache;
use Arbory\Base\Support\Activation\HasActivationDates;
use Baum\NestedSet\Node as BaumNode;
use Database\Factories\Nodes\BaseNodeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Node.
 *
 * @property Model|null $content
 * @property string $name
 * @property string $id
 * @property int $content_id
 * @property string $content_type
 * @property string $slug
 * @property bool $active
 * @property int $item_position
 * @property string $parent_id
 * @property string $locale
 * @property string $meta_title
 * @property string $meta_author
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $activate_at
 * @property string $expire_at
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 */
class Node extends Model
{
    use BaumNode;
    use HasActivationDates;
    use HasFactory;
    use HasUuids;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $leftColumnName = 'lft';

    /**
     * @var string
     */
    protected $rightColumnName = 'rgt';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'content_type',
        'content_id',
        'item_position',
        'locale',
        'meta_title',
        'meta_author',
        'meta_keywords',
        'meta_description',
        'activate_at',
        'expire_at',
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $options = [])
    {
        NodeRoutesCache::setLastUpdateTimestamp(time());

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|PageInterface
     */
    public function content()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Support\Collection|Node[]
     */
    public function parents()
    {
        return $this->ancestors()->get();
    }

    /**
     * Use ancestors() instead.
     *
     * @deprecated
     *
     * @return Builder
     */
    public function parentsQuery()
    {
        return $this->newQuery()
            ->where($this->getLeftColumnName(), '<', (int) $this->getLeft())
            ->where($this->getRightColumnName(), '>', (int) $this->getRight())
            ->orderBy($this->getDepthColumnName(), 'asc');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $uri = [];

        foreach ($this->parents() as $parent) {
            $uri[] = $parent->getSlug();
        }

        $uri[] = $this->getSlug();

        return implode('/', $uri);
    }

    /**
     * @param  $name
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string|null
     */
    public function getUrl($name, array $parameters = [], $absolute = true)
    {
        $routes = app('routes');
        $routeName = 'node.'.$this->getKey().'.'.$name;
        $route = $routes->getByName($routeName);

        return $route ? route($routeName, $parameters, $absolute) : null;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function getActiveAttribute()
    {
        if (! $this->isPublic()) {
            return false;
        }

        if ($this->parents()->isNotEmpty()) {
            return $this->parent()->first()->isActive();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->hasActivated() && ! $this->hasExpired();
    }

    /**
     * Return parent id (legacy support).
     *
     * @return mixed
     */
    public function getParentId()
    {
        return $this->getAttribute($this->getParentColumnName());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return BaseNodeFactory::new();
    }
}
