<?php

namespace Arbory\Base\Repositories;

use Settings;
use Arbory\Base\Nodes\Node;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class NodesRepository.
 */
class NodesRepository extends AbstractModelsRepository
{
    /**
     * @var bool
     */
    protected $onlyActiveNodes = false;

    /**
     * @var string
     */
    protected $modelClass = Node::class;

    /**
     * @return Builder
     */
    public function newQuery()
    {
        $query = parent::newQuery();

        if ($this->isQueryingOnlyActiveNodes()) {
            $query->active();
        }

        return $query;
    }

    /**
     * @param Node $node
     * @param string|null $key
     * @param mixed|null $value
     * @return Builder
     */
    public function findUnder(Node $node, string $key = null, $value = null)
    {
        $query = $this->newQuery()
            ->whereBetween($node->getLeftColumnName(), [$node->getLeft() + 1, $node->getRight() - 1])
            ->whereBetween($node->getRightColumnName(), [$node->getLeft() + 1, $node->getRight() - 1]);

        if ($key && $value) {
            $query->where($key, $value);
        }

        return $query;
    }

    /**
     * @param Node $node
     * @param string|null $key
     * @param mixed|null $value
     * @return Builder
     */
    public function findAbove(Node $node, string $key = null, $value = null)
    {
        $query = $this->newQuery()
            ->where($node->getLeftColumnName(), '<=', $node->getLeft())
            ->where($node->getRightColumnName(), '>=', $node->getRight());

        if ($key && $value) {
            $query->where($key, $value);
        }

        return $query;
    }

    /***
     * @param $uri
     * @return Node|null
     */
    public function findBySlug($uri)
    {
        $parts = explode('/', trim($uri, '/'));

        $node = null;

        foreach ($parts as $depth => $part) {
            $query = $this->newQuery()->where('depth', $depth)->where('slug', $part);

            if ($node instanceof Node) {
                $query->whereBetween($node->getLeftColumnName(), [$node->getLeft() + 1, $node->getRight() - 1]);
                $query->whereBetween($node->getRightColumnName(), [$node->getLeft() + 1, $node->getRight() - 1]);
            }

            $nodes = $query->get();

            if ($nodes->count() !== 1) {
                break;
            }

            $node = $nodes->first();
        }

        return $node;
    }

    /**
     * @return mixed
     */
    public function getLastUpdateTimestamp()
    {
        return Settings::get('nodes.last_update');
    }

    /**
     * @param int $time
     * @return void
     */
    public function setLastUpdateTimestamp(int $time)
    {
        Settings::set('nodes.last_update', $time);
    }

    /**
     * @return bool
     */
    public function isQueryingOnlyActiveNodes(): bool
    {
        return $this->onlyActiveNodes;
    }

    /**
     * @param bool $onlyActiveNodes
     */
    public function setQueryOnlyActiveNodes(bool $onlyActiveNodes)
    {
        $this->onlyActiveNodes = $onlyActiveNodes;
    }
}
