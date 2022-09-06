<?php

namespace Arbory\Base\Services\Content;

use Arbory\Base\Exceptions\BadMethodCallException;
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

    public function __construct(protected ContentTypeRegister $contentTypeRegister, protected ContentTypeRoutesRegister $contentTypeRoutesRegister)
    {
    }

    /**
     * @throws BadMethodCallException
     */
    public function register(string $model): self
    {
        $this->definition = new ContentTypeDefinition($model);

        $this->contentTypeRegister->register($this->definition);

        return $this->fields(function () {
        });
    }

    public function get(string $model): self
    {
        $this->definition = $this->contentTypeRegister->findByModelClass($model);

        return $this;
    }

    /**
     * @throws BadMethodCallException
     */
    public function routes(Closure $routes): self
    {
        $this->contentTypeRoutesRegister->register($this->definition->getModel(), $routes);

        return $this;
    }

    public function fields(Closure $fieldSet): self
    {
        $this->definition->setFieldSetHandler($fieldSet);

        return $this;
    }

    public function layout(Closure $layout): self
    {
        $this->definition->setLayoutHandler($layout);

        return $this;
    }
}
