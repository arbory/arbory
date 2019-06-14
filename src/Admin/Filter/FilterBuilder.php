<?php


namespace Arbory\Base\Admin\Filter;


use Illuminate\Database\Query\Builder;

class FilterBuilder
{
    /**
     * @var FilterCollection
     */
    protected $filters;

    /**
     * @var FilterTypeFactory
     */
    protected $filterTypeFactory;

    /**
     * @var Parameters
     */
    protected $parameters;

    public function __construct(FilterTypeFactory $filterTypeFactory)
    {
        $this->filters = new FilterCollection();
        $this->filterTypeFactory = $filterTypeFactory;
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $filterType
     * @param iterable $filterTypeConfiguration
     *
     * @return FilterItem
     */
    public function addFilter(string $name, string $title, string $filterType, iterable $filterTypeConfiguration): FilterItem
    {
        $filterItem = new FilterItem();
        $filterItem
            ->setName($name)
            ->setTitle($title)
            ->setType(
                $this->filterTypeFactory->make($filterType, $filterTypeConfiguration)
            );

        $this->filters->push($filterItem);

        return $filterItem;
    }

    /**
     * @return FilterCollection
     */
    public function getFilters(): FilterCollection
    {
        return $this->filters;
    }

    /**
     * @param Parameters $parameters
     * @return FilterBuilder
     */
    public function setParameters(Parameters $parameters): FilterBuilder
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return Parameters
     */
    public function getParameters(): Parameters
    {
        return $this->parameters;
    }

    public function apply(Builder $builder)
    {

    }

    public function render()
    {

    }
}