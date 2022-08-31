<?php

namespace Arbory\Base\Admin\Filter\Parameters;

use Arbory\Base\Admin\Filter\Parameters\Transformers\ParameterTransformerInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

class ParameterTransformerPipeline
{
    /**
     * @var Pipeline
     */
    protected $pipeline;

    /**
     * @var FilterParameters
     */
    protected $parameters;

    /**
     * @var ParameterTransformerInterface[]
     */
    protected $transformers;

    public function __construct(Container $container)
    {
        $this->pipeline = new Pipeline($container);
    }

    public function execute(): FilterParameters
    {
        return $this->pipeline
            ->through($this->transformers)
            ->via('transform')
            ->send($this->parameters)
            ->then(fn($passable) => $passable);
    }

    public function addTransformer(callable|\Arbory\Base\Admin\Filter\Parameters\Transformers\ParameterTransformerInterface $transformer): self
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @param  ParameterTransformerInterface[]  $transformers
     */
    public function setTransformers(array $transformers): self
    {
        $this->transformers = $transformers;

        return $this;
    }

    public function setParameters(FilterParameters $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters(): FilterParameters
    {
        return $this->parameters;
    }
}
