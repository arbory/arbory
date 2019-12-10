<?php

namespace Arbory\Base\Admin\Filter;

use Arbory\Base\Admin\Filter\Models\SavedFilter;
use Arbory\Base\Admin\Filter\Repositories\SavedFilterRepository;
use Illuminate\Database\Eloquent\Builder;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Arbory\Base\Admin\Filter\Parameters\FilterParameterResolver;
use Arbory\Base\Admin\Filter\Parameters\ParameterTransformerPipeline;
use Arbory\Base\Admin\Module;
use Illuminate\Support\Collection;

class FilterManager
{
    /**
     * @var FilterCollection
     */
    protected $filters;

    /**
     * @var FilterFactory
     */
    protected $filterTypeFactory;

    /**
     * @var FilterExecutor
     */
    protected $filterExecutor;

    /**
     * @var FilterParameterResolver
     */
    protected $filterParameterResolver;

    /**
     * @var SavedFilterRepository
     */
    protected $savedFilterRepository;

    /**
     * @var FilterParameters
     */
    protected $parameters;

    /**
     * @var ParameterTransformerPipeline[]|callable[]|string[]
     */
    protected $transformers = [];

    /**
     * FilterManager constructor.
     * @param FilterFactory $filterTypeFactory
     * @param FilterExecutor $filterExecutor
     * @param FilterParameterResolver $filterParameterResolver
     * @param SavedFilterRepository $savedFilterRepository
     */
    public function __construct(
        FilterFactory $filterTypeFactory,
        FilterExecutor $filterExecutor,
        FilterParameterResolver $filterParameterResolver,
        SavedFilterRepository $savedFilterRepository
    ) {
        $this->filters = new FilterCollection();
        $this->filterTypeFactory = $filterTypeFactory;
        $this->filterExecutor = $filterExecutor;
        $this->filterParameterResolver = $filterParameterResolver;
        $this->savedFilterRepository = $savedFilterRepository;
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $filterType
     * @param iterable $filterTypeConfig
     *
     * @return FilterItem
     */
    public function addFilter(
        string $name,
        string $title,
        string $filterType,
        iterable $filterTypeConfig = []
    ): FilterItem {
        $filterItem = $this->filterTypeFactory->makeSimpleFilter($filterType, $name, $filterTypeConfig)
            ->setManager($this)
            ->setTitle($title);

        $this->filters->push($filterItem);

        return $filterItem;
    }

    /**
     * @return FilterCollection|FilterItem[]
     */
    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }

    /**
     * @return FilterParameters
     */
    public function getParameters(): FilterParameters
    {
        if ($this->parameters === null) {
            return $this->parameters = $this->filterParameterResolver->resolve($this);
        }

        return $this->parameters;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $this->filterExecutor->execute($this, $builder);
    }

    /**
     * @return ParameterTransformerPipeline[]|callable[]|string[]
     */
    public function getTransformers(): array
    {
        return $this->transformers;
    }

    /**
     * @param $transformer
     * @return FilterManager
     */
    public function addTransformer($transformer): self
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @param Module $module
     * @return SavedFilter[]|Collection
     */
    public function getSavedFilters(Module $module): Collection
    {
        return $this->savedFilterRepository->findByModule($module);
    }
}
