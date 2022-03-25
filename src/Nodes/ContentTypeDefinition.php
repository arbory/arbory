<?php

namespace Arbory\Base\Nodes;

use Arbory\Base\Support\Nodes\NameGenerator;
use Closure;

class ContentTypeDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var \Closure
     */
    protected $fieldSetHandler;

    /**
     * @var \Closure
     */
    protected $layoutHandler;

    /**
     * @param  string  $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
        $this->name = $this->makeNameFromType($model);

        $this->layoutHandler = function () {
        };
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return Closure
     */
    public function getFieldSetHandler(): Closure
    {
        return $this->fieldSetHandler;
    }

    /**
     * @param  Closure  $fieldSetHandler
     */
    public function setFieldSetHandler(Closure $fieldSetHandler)
    {
        $this->fieldSetHandler = $fieldSetHandler;
    }

    /**
     * @param  Closure  $layoutHandler
     */
    public function setLayoutHandler(Closure $layoutHandler): void
    {
        $this->layoutHandler = $layoutHandler;
    }

    /**
     * @return Closure
     */
    public function getLayoutHandler(): Closure
    {
        return $this->layoutHandler;
    }

    /**
     * @param  string  $type
     * @return string
     */
    protected function makeNameFromType($type): string
    {
        return app(NameGenerator::class)->generate($type);
    }
}
