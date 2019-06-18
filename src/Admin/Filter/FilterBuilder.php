<?php


namespace Arbory\Base\Admin\Filter;


use Arbory\Base\Admin\Filter\Concerns\WithParameterValidation;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Html\Elements\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class FilterBuilder
{
    use Renderable;

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

    /**
     * @var FilterExecutor
     */
    protected $filterExecutor;

    public function __construct(FilterTypeFactory $filterTypeFactory)
    {
        $this->filters = new FilterCollection();
        $this->filterTypeFactory = $filterTypeFactory;
        $this->renderer = new Renderer();
        $this->parameters = new Parameters();
        $this->filterExecutor = new FilterExecutor($this);
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
            ->setNamespace($this->getParameters()->getNamespace())
            ->setName($name)
            ->setTitle($title)
            ->setType(
                $this->filterTypeFactory->make($filterType, $filterTypeConfiguration)
            );

        $this->filters->push($filterItem);

        return $filterItem;
    }

    public function addFromTarget(CustomizedTargetInterface $target)
    {
        $filterItem = new FilterItem();


    }

    /**
     * @return FilterCollection|FilterItem[]
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

    /**
     *
     */
    protected function populateParameters(): void
    {
        $parameters = $this->getParameters();

        $parameters->replace(
            request()->input($parameters->getNamespace(), []) // TODO: Implement system which would allow populating filters from multiple sources
        );

        foreach($this->getFilters() as $filterItem) {
            $value = $parameters->get($filterItem->getName());
            $type = $filterItem->getType();

            if($type instanceof WithParameterValidation) {
                // TODO: Move a validator class
                $validator = validator()->make([
                    $filterItem->getName() => $value
                ], $this->normalizeRules($filterItem,
                    $type->rules($parameters, function (string $attribute) use ($filterItem) {
                        return $filterItem->getName() . "." . $attribute;
                    })));

                if($validator->fails()) {
                    $value = $filterItem->getDefaultValue();

                    $parameters->offsetSet($filterItem->getName(), $value);
                }
            }

            $filterItem->getType()->setValue(
                $value
            );
        }
    }

    protected function normalizeRules(FilterItem $filterItem, array $rules): array {
        if(! Arr::isAssoc($rules)) {
            return [
                $filterItem->getName() => $rules
            ];
        }

        $rulesNormalized = [];

        foreach($rules as $field => $ruleList) {
            $rulesNormalized["{$filterItem->getName()}.{$field}"] = $ruleList;
        }

        return $rulesNormalized;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->populateParameters();

        $this->filterExecutor->execute($builder);
    }

    /**
     * @return Content|string
     */
    public function render()
    {
        $this->renderer->setBuilder($this);

        return $this->renderer->render();
    }
}