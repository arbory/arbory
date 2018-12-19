<?php

namespace Arbory\Base\Nodes;

use Alsofronie\Uuid\UuidModelTrait;
use Arbory\Base\Pages\PageInterface;
use Arbory\Base\Repositories\NodesRepository;
use Illuminate\Database\Query\Builder;

/**
 * Class Node
 * @package Arbory\Base\Nodes
 */
class Node extends \Baum\Node
{
    use UuidModelTrait;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'content_type',
        'content_id',
        'item_position',
        'activate_at',
        'active',
        'locale',
        'meta_title',
        'meta_author',
        'meta_keywords',
        'meta_description'
    ];

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * {{@inheritdoc}}
     */
    public function save( array $options = [] )
    {
        ( new NodesRepository )->setLastUpdateTimestamp( time() );

        return parent::save( $options );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|PageInterface
     */
    public function content()
    {
        return $this->morphTo();
    }

    /**
     * @return NodeCollection|\Illuminate\Support\Collection|static[]
     */
    public function parents()
    {
        if( !$this->relationLoaded( 'parents') )
        {
            $this->setRelation('parents', $this->parentsQuery()->get() );
        }

        return $this->getRelation('parents');
    }

    /**
     * @return Builder
     */
    public function parentsQuery()
    {
        return $this->newQuery()
            ->where( $this->getLeftColumnName(), '<', (int) $this->getLeft() )
            ->where( $this->getRightColumnName(), '>', (int) $this->getRight() )
            ->orderBy( $this->getDepthColumnName(), 'asc' );
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
     * @param array $models
     * @return NodeCollection
     */
    public function newCollection( array $models = array() )
    {
        return new NodeCollection( $models );
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $uri = [ ];

        foreach( $this->parents() as $parent )
        {
            $uri[] = $parent->getSlug();
        }

        $uri[] = $this->getSlug();

        return implode( '/', $uri );
    }

    /**
     * @param $name
     * @param array $parameters
     * @param bool $absolute
     * @return string|null
     */
    public function getUrl( $name, array $parameters = [], $absolute = true )
    {
        $routes = app( 'routes' );
        $routeName = 'node.' . $this->getKey() . '.' . $name;
        $route = $routes->getByName( $routeName );

        return $route ? route( $routeName, $parameters, $absolute ) : null;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $active = $this->active;

        if( !$this->active )
        {
            return false;
        }

        if( $this->parents()->isEmpty() )
        {
            return $active;
        }

        return $this->parent()->first()->isActive();
    }
}
