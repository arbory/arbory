<?php

namespace Arbory\Base\Admin\Filter;

use Arbory\Base\Admin\Filter\Models\SavedFilter;
use Arbory\Base\Admin\Filter\Repositories\SavedFilterRepository;
use Arbory\Base\Admin\ModuleComponent;
use Illuminate\Database\Eloquent\Builder;
use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Arbory\Base\Admin\Filter\Parameters\FilterParameterResolver;
use Arbory\Base\Admin\Filter\Parameters\ParameterTransformerPipeline;
use Illuminate\Support\Collection;

class FilterManager
{
    use ModuleComponent;

    /**
     * @var FilterCollection
     */
    protected $filters;

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
     */
    public function __construct(
        protected FilterFactory $filterTypeFactory,
        protected FilterExecutor $filterExecutor,
        protected FilterParameterResolver $filterParameterResolver,
        protected SavedFilterRepository $savedFilterRepository
    ) {
        $this->filters = new FilterCollection();
    }

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

    public function getParameters(): FilterParameters
    {
        if ($this->parameters === null) {
            return $this->parameters = $this->filterParameterResolver->resolve($this);
        }

        return $this->parameters;
    }

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
     */
    public function addTransformer($transformer): self
    {
        $this->transformers[] = $transformer;

        return $this;
    }

    /**
     * @return SavedFilter[]|Collection
     */
    public function getSavedFilters(): Collection
    {
        return $this->savedFilterRepository->findByModule($this->getModule());
    }
}
