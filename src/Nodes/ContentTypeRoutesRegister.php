<?php

namespace Arbory\Base\Nodes;

use Arbory\Base\Exceptions\BadMethodCallException;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

/**
 * Class Router.
 */
class ContentTypeRoutesRegister
{
    /**
     * @var array|Closure[]
     */
    protected array $contentTypeHandlers = [];

    public function __construct(protected ContentTypeRegister $contentTypesRegister)
    {
    }

    /**
     * @param $contentType
     * @return $this
     * @throws BadMethodCallException
     */
    public function register($contentType, Closure $handler)
    {
        if (!$this->contentTypesRegister->isValidContentType($contentType)) {
            throw new BadMethodCallException('Invalid content type');
        }

        $this->contentTypeHandlers[$contentType] = $handler;

        return $this;
    }

    /**
     * @param $contentType
     * @return Closure
     */
    public function getContentTypeHandler($contentType)
    {
        if (!array_key_exists($contentType, $this->contentTypeHandlers)) {
            return function () {
            };
        }

        return $this->contentTypeHandlers[$contentType];
    }

    /**
     * @return \Illuminate\Routing\Router
     */
    public function getRouter()
    {
        return app('router');
    }

    /**
     * @return Node|null
     */
    public function getCurrentNode()
    {
        if (!$this->getRouter()->getCurrentRoute()) {
            return;
        }

        return $this->getNodeFromRoute($this->getRouter()->getCurrentRoute());
    }

    /**
     * @return Node|null
     */
    public function getNodeFromRoute(Route $route)
    {
        $currentRouteName = $route->getName();

        if (!preg_match('#^node\.(?P<id>.*?)\.#', $currentRouteName, $matches)) {
            return;
        }

        return Node::with('content')->find($matches['id']);
    }

    /**
     * @return void
     */
    public function registerNodes(): void
    {
        $this->registerRoutesForNodeCollection(Node::all()->unorderedHierarchicalList());
    }

    protected function registerRoutesForNodeCollection(Collection $items, string $base = ''): void
    {
        foreach ($items as $item) {
            $slug = $base . '/' . $item->getSlug();

            if (!$item->active) {
                if (config('arbory.preview.enabled')) {
                    $this->registerPreviewRoutes($item, $slug);
                }
                continue;
            }

            $this->registerNodeRoutes($item, $slug);

            if ($item->children->count()) {
                $this->registerRoutesForNodeCollection($item->children, $slug);
            }
        }
    }

    protected function registerPreviewRoutes(Node $item, string $slug): void
    {
        $this->registerNodeRoutes($item, 'preview-' . sha1(config('arbory.preview.slug_salt') . $slug));

        if ($item->children->count()) {
            $this->registerPreviewRoutesForNodeCollection($item->children, $slug);
        }
    }

    protected function registerPreviewRoutesForNodeCollection(Collection $items, string $base = ''): void
    {
        foreach ($items as $item) {
            $slug = $base . '/' . $item->getSlug();

            $this->registerNodeRoutes($item, 'preview-' . sha1(config('arbory.preview.slug_salt') . $slug));

            if ($item->children->count()) {
                $this->registerPreviewRoutesForNodeCollection($item->children, $slug);
            }
        }
    }

    protected function registerNodeRoutes(Node $node, string $slug): void
    {
        $attributes = [
            'as' => 'node.' . $node->getKey() . '.',
            'prefix' => $slug,
            'namespace' => false,
            'middleware' => 'web',
        ];

        $this->getRouter()->group($attributes, $this->getContentTypeHandler($node->getContentType()));
    }
}
