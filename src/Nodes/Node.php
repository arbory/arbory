<?php

namespace Arbory\Base\Nodes;

use Arbory\Base\Pages\PageInterface;
use Arbory\Base\Repositories\NodesRepository;
use Arbory\Base\Support\Activation\HasActivationDates;
use Baum\NestedSet\Node as BaumNode;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

/**
 * Class Node.
 *
 * @property Model|null $content
 * @property string $name
 * @property string $id
 * @property string $content_type
 * @property string $slug
 * @property bool $active
 */
class Node extends Model
{
    use Uuid;
    use HasActivationDates;
    use BaumNode;

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
    protected string $leftColumnName = 'lft';

    /**
     * @var string
     */
    protected string $rightColumnName = 'rgt';

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
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $options = [])
    {
        (new NodesRepository)->setLastUpdateTimestamp(time());

        return parent::save($options);
    }

    public function content(): MorphTo|PageInterface
    {
        return $this->morphTo();
    }

    /**
     * @return Collection|Node[]
     */
    public function parents(): Collection|array
    {
        return $this->ancestors()->get();
    }

    /**
     * Use ancestors() instead.
     *
     * @deprecated
     */
    public function parentsQuery(): Builder
    {
        return $this->newQuery()
            ->where($this->getLeftColumnName(), '<', $this->getLeft())
            ->where($this->getRightColumnName(), '>', $this->getRight())
            ->orderBy($this->getDepthColumnName());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContentType(): string
    {
        return $this->content_type;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getUri(): string
    {
        $uri = [];

        foreach ($this->parents() as $parent) {
            $uri[] = $parent->getSlug();
        }

        $uri[] = $this->getSlug();

        return implode('/', $uri);
    }

    public function getUrl(string $name, array $parameters = [], bool $absolute = true): ?string
    {
        $routes = app('routes');
        $routeName = 'node.' . $this->getKey() . '.' . $name;
        $route = $routes->getByName($routeName);

        return $route ? route($routeName, $parameters, $absolute) : null;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getActiveAttribute(): bool
    {
        if (! $this->isPublic()) {
            return false;
        }

        if ($this->parents()->isNotEmpty()) {
            return $this->parent()->first()->isActive();
        }

        return true;
    }

    public function isPublic(): bool
    {
        return $this->hasActivated() && ! $this->hasExpired();
    }

    /**
     * Return parent id (legacy support).
     */
    public function getParentId(): mixed
    {
        return $this->getAttribute($this->getParentColumnName());
    }
}
