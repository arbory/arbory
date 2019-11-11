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

    /**
     * @return FilterParameters
     */
    public function execute(): FilterParameters
    {
        return $this->pipeline
            ->through($this->transformers)
            ->via('transform')
            ->send($this->parameters)
            ->then(function ($passable) {
                return $passable;
            });
    }

    /**
     * @param callable|ParameterTransformerInterface $transformer
     *
     * @return ParameterTransformerPipeline
     */
    public function addTransformer($transformer): self
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @param ParameterTransformerInterface[] $transformers
     * @return ParameterTransformerPipeline
     */
    public function setTransformers(array $transformers): self
    {
        $this->transformers = $transformers;

        return $this;
    }

    /**
     * @param FilterParameters $parameters
     * @return ParameterTransformerPipeline
     */
    public function setParameters(FilterParameters $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return FilterParameters
     */
    public function getParameters(): FilterParameters
    {
        return $this->parameters;
    }
}
