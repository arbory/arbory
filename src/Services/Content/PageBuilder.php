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
     * @return $this
     * @throws BadMethodCallException
     */
    public function register(string $model)
    {
        $this->definition = new ContentTypeDefinition($model);

        $this->contentTypeRegister->register($this->definition);

        return $this->fields(function () {
        });
    }

    /**
     * @return $this
     */
    public function get(string $model)
    {
        $this->definition = $this->contentTypeRegister->findByModelClass($model);

        return $this;
    }

    /**
     * @return $this
     * @throws BadMethodCallException
     */
    public function routes(Closure $routes)
    {
        $this->contentTypeRoutesRegister->register($this->definition->getModel(), $routes);

        return $this;
    }

    /**
     * @return $this
     * @throws BadMethodCallException
     */
    public function fields(Closure $fieldSet)
    {
        $this->definition->setFieldSetHandler($fieldSet);

        return $this;
    }

    /**
     * @return $this
     */
    public function layout(Closure $layout)
    {
        $this->definition->setLayoutHandler($layout);

        return $this;
    }
}
