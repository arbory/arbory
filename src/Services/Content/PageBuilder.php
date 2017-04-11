<?php

namespace CubeSystems\Leaf\Services\Content;

use Closure;
use CubeSystems\Leaf\Nodes\ContentTypeRegister;
use CubeSystems\Leaf\Nodes\ContentTypeRoutesRegister;

class PageBuilder
{
    /**
     * @var string
     */
    protected $page;

    /**
     * @var ContentTypeRegister
     */
    protected $contentTypeRegister;

    /**
     * @var ContentTypeRoutesRegister
     */
    protected $contentTypeRoutesRegister;

    /**
     * @param ContentTypeRegister $contentTypeRegister
     * @param ContentTypeRoutesRegister $contentTypeRoutesRegister
     */
    public function __construct(
        ContentTypeRegister $contentTypeRegister,
        ContentTypeRoutesRegister $contentTypeRoutesRegister
    )
    {
        $this->contentTypeRegister = $contentTypeRegister;
        $this->contentTypeRoutesRegister = $contentTypeRoutesRegister;
    }

    /**
     * @param string $page
     * @return $this
     */
    public function register( string $page )
    {
        $this->page = $page;

        $this->contentTypeRegister->register( $this->page );

        return $this;
    }

    /**
     * @param Closure $routes
     * @return $this
     * @throws \CubeSystems\Leaf\Exceptions\BadMethodCallException
     */
    public function routes( Closure $routes )
    {
        $this->contentTypeRoutesRegister->register( $this->page, $routes );

        return $this;
    }

}