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
     * @var \Closure
     */
    protected $fieldSetHandler;

    /**
     * @var \Closure
     */
    protected $layoutHandler;

    public function __construct(protected string $model)
    {
        $this->name = $this->makeNameFromType($model);

        $this->layoutHandler = function () {
        };
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getFieldSetHandler(): Closure
    {
        return $this->fieldSetHandler;
    }

    public function setFieldSetHandler(Closure $fieldSetHandler)
    {
        $this->fieldSetHandler = $fieldSetHandler;
    }

    public function setLayoutHandler(Closure $layoutHandler): void
    {
        $this->layoutHandler = $layoutHandler;
    }

    public function getLayoutHandler(): Closure
    {
        return $this->layoutHandler;
    }

    /**
     * @param  string  $type
     */
    protected function makeNameFromType($type): string
    {
        return app(NameGenerator::class)->generate($type);
    }
}
