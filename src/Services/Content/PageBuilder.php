<?php

namespace Arbory\Base\Services\Content;

use Closure;
use Arbory\Base\Nodes\ContentTypeRegister;
use Arbory\Base\Nodes\ContentTypeDefinition;
use Arbory\Base\Nodes\ContentTypeRoutesRegister;

class PageBuilder
{
    /**
     * @var ContentTypeDefinition
     */
    protected $definition;

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
    ) {
        $this->contentTypeRegister = $contentTypeRegister;
        $this->contentTypeRoutesRegister = $contentTypeRoutesRegister;
    }

    /**
     * @param string $model
     * @return $this
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function register(string $model)
    {
        $this->definition = new ContentTypeDefinition($model);

        $this->contentTypeRegister->register($this->definition);

        return $this->fields(function () {
        });
    }

    /**
     * @param string $model
     * @return $this
     */
    public function get(string $model)
    {
        $this->definition = $this->contentTypeRegister->findByModelClass($model);

        return $this;
    }

    /**
     * @param Closure $routes
     * @return $this
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function routes(Closure $routes)
    {
        $this->contentTypeRoutesRegister->register($this->definition->getModel(), $routes);

        return $this;
    }

    /**
     * @param Closure $fieldSet
     * @return $this
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    public function fields(Closure $fieldSet)
    {
        $this->definition->setFieldSetHandler($fieldSet);

        return $this;
    }

    /**
     * @param Closure $layout
     *
     * @return $this
     */
    public function layout(Closure $layout)
    {
        $this->definition->setLayoutHandler($layout);

        return $this;
    }
}
