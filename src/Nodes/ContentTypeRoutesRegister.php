<?php

namespace CubeSystems\Leaf\Nodes;

use Closure;
use CubeSystems\Leaf\Exceptions\BadMethodCallException;
use Illuminate\Support\Collection;

/**
 * Class Router
 * @package CubeSystems\Leaf\Nodes\Routing
 */
class ContentTypeRoutesRegister
{
    /**
     * @var array|Closure[]
     */
    protected $contentTypeHandlers = [];

    /**
     * @var ContentTypeRegister
     */
    protected $contentTypesRegister;

    /**
     * @param ContentTypeRegister $contentTypeRegister
     */
    public function __construct( ContentTypeRegister $contentTypeRegister )
    {
        $this->contentTypesRegister = $contentTypeRegister;
    }

    /**
     * @param $contentType
     * @param Closure $handler
     * @return $this
     * @throws BadMethodCallException
     */
    public function register( $contentType, Closure $handler )
    {
        if( !$this->contentTypesRegister->isValidContentType( $contentType ) )
        {
            throw new BadMethodCallException( 'Invalid content type' );
        }

        $this->contentTypeHandlers[$contentType] = $handler;

        return $this;
    }

    /**
     * @param $contentType
     * @return Closure
     */
    public function getContentTypeHandler( $contentType )
    {
        if( !array_key_exists( $contentType, $this->contentTypeHandlers ) )
        {
            return function ()
            {

            };
        }

        return $this->contentTypeHandlers[$contentType];
    }

    /**
     * @return \Illuminate\Routing\Router
     */
    public function getRouter()
    {
        return app( 'router' );
    }

    /**
     * @return Node|null
     */
    public function getCurrentNode()
    {
        if ( !$this->getRouter()->getCurrentRoute() )
        {
            return null;
        }
        
        $currentRouteName = $this->getRouter()->getCurrentRoute()->getName();

        if( !preg_match( '#^node\.(?P<id>.*?)\.#', $currentRouteName, $matches ) )
        {
            return null;
        }

        return Node::with( 'content' )->find( $matches['id'] );
    }

    public function registerNodes()
    {
        $this->registerRoutesForNodeCollection( Node::all()->unorderedHierarchicalList() );
    }

    /**
     * @param NodeCollection|Node[] $items
     * @param string $base
     */
    protected function registerRoutesForNodeCollection( Collection $items, $base = '' )
    {
        foreach( $items as $item )
        {
            $slug = $base . '/' . $item->getSlug();

            $this->registerNodeRoutes( $item, $slug );

            if( $item->children->count() )
            {
                $this->registerRoutesForNodeCollection( $item->children, $slug );
            }
        }
    }

    /**
     * @param Node $node
     * @param $slug
     */
    protected function registerNodeRoutes( Node $node, $slug )
    {
        $attributes = [
            'as' => 'node.' . $node->getKey() . '.',
            'prefix' => $slug,
            'namespace' => false,
            'middleware' => 'web'
        ];

        $this->getRouter()->group( $attributes, $this->getContentTypeHandler( $node->getContentType() ) );
    }
}
