<?php

namespace Arbory\Base\Nodes\Routing;

use Closure;
use Arbory\Base\Nodes\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Application;
use Arbory\Base\Repositories\NodesRepository;

/**
 * Class Router.
 */
class Router
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var NodesRepository
     */
    protected $nodes;

    /**
     * @var Node
     */
    protected $currentNode;

    /**
     * @var array|Closure[]
     */
    protected $contentTypes = [];

    /**
     * Router constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $contentType
     * @param Closure $callback
     * @return $this
     */
    public function add($contentType, $callback)
    {
        $this->contentTypes[$contentType] = $callback;

        return $this;
    }

    /**
     * @return Node
     */
    public function getCurrentNode()
    {
        return $this->currentNode;
    }

    /**
     * @return NodesRepository
     */
    protected function getNodes()
    {
        if ($this->nodes === null) {
            $this->nodes = $this->app->make(NodesRepository::class);
        }

        return $this->nodes;
    }

    /**
     * @param Request $request
     * @return Node|null
     */
    public function findNode(Request $request)
    {
        $path = $request->path() === '/' ? '/' : '/'.$request->path();

        $this->currentNode = $this->getNodes()->findBySlug($path);

        return $this->currentNode;
    }

    /**
     * @param Request $request
     */
    public function register(Request $request)
    {
        $this->registerContentTypes($this->getContentTypes());

        $node = $this->findNode($request);

        if ($node) {
            $this->registerRouteForNode($node);
        }
    }

    /**
     * @param array $contentTypes
     */
    protected function registerContentTypes(array $contentTypes)
    {
        foreach ($contentTypes as $contentType) {
            $attributes = [
                'as' => $contentType.'::',
                'prefix' => '{slug}',
            ];

            $this->getRouter()->group($attributes, $this->getContentTypeHandler($contentType));
        }
    }

    /**
     * @param Node $node
     */
    protected function registerRouteForNode(Node $node)
    {
        $routes = $this->findRoutesForNode($node);
        $slug = $node->getUri();

        $routesCollection = $this->getRouter()->getRoutes();
        $request = $this->app['request'];

        foreach ($routes as $route) {
            $clone = clone $route;
            $clone->setUri(str_replace('{slug}', $slug, $route->uri()));
            $clone->bind($request);

            $routesCollection->add($clone);
        }
    }

    /**
     * @param Node $node
     * @return Collection|\Illuminate\Routing\Route[]
     */
    protected function findRoutesForNode(Node $node)
    {
        $routes = $this->getRouter()->getRoutes()->getIterator();

        $nodeRoutes = new Collection();

        foreach ($routes as $route) {
            if (starts_with($route->getName(), $node->getContentType())) {
                $nodeRoutes->push($route);
            }
        }

        return $nodeRoutes;
    }

    /**
     * @return array
     */
    public function getContentTypes()
    {
        return array_keys($this->contentTypes);
    }

    /**
     * @param $contentType
     * @return Closure|null
     */
    public function getContentTypeHandler($contentType)
    {
        if (! array_key_exists($contentType, $this->contentTypes)) {
            return;
        }

        return $this->contentTypes[$contentType];
    }

    /**
     * @return \Illuminate\Routing\Router
     */
    public function getRouter()
    {
        return $this->app['router'];
    }
}
