<?php


namespace Arbory\Base\Admin\Filter;


use Arbory\Base\Admin\Filter\Parameters\FilterParameters;
use Arbory\Base\Admin\Filter\Parameters\ParameterTransformerPipeline;
use Arbory\Base\Admin\Filter\Parameters\Transformers\QueryStringTransformer;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Html\Elements\Content;
use Illuminate\Database\Eloquent\Builder;

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
     * @var FilterParameters
     */
    protected $parameters;

    /**
     * @var FilterExecutor
     */
    protected $filterExecutor;

    /**
     * @var FilterValidator
     */
    protected $validator;

    public function __construct(FilterTypeFactory $filterTypeFactory)
    {
        $this->filters = new FilterCollection();
        $this->filterTypeFactory = $filterTypeFactory;
        $this->renderer = new Renderer();
        $this->parameters = new FilterParameters();
        $this->filterExecutor = new FilterExecutor($this);

        // TODO: Refactor properly to support change better
        $this->validator = new FilterValidator(validator(), $this->parameters, $this->filters);
    }

    /**
     * @param string $name
     * @param string $title
     * @param string $filterType
     * @param iterable $filterTypeConfig
     *
     * @return FilterItem
     */
    public function addFilter(string $name, string $title, string $filterType, iterable $filterTypeConfig = []): FilterItem
    {
        $filterItem = new FilterItem();
        $filterItem
            ->setNamespace($this->getParameters()->getNamespace())
            ->setName($name)
            ->setTitle($title)
            ->setType(
                $this->filterTypeFactory->make($filterType, $filterTypeConfig)
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
     * @param FilterParameters $parameters
     * @return FilterBuilder
     */
    public function setParameters(FilterParameters $parameters): FilterBuilder
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

    /**
     * TODO: Move to a configurable builder which would allow to define how parameters are added
     */
    protected function populateParameters(): void
    {
        $this->setParameters(
            (new ParameterTransformerPipeline(app()))
                ->setParameters($this->getParameters())
                ->addTransformer(QueryStringTransformer::class)
                ->execute()
        );

        $parameters = $this->getParameters();
        $validator = $this->validator->getValidator();
        $errors = $validator->getMessageBag();

        foreach($this->getFilters() as $filterItem) {
            $value = $parameters->get($filterItem->getName());

            if($errors->has($filterItem->getName() . '.*')) {
                $value = $filterItem->getDefaultValue();

                $parameters->set($filterItem->getName(), $value);
            }


            $filterItem->getType()->setValue(
                $value
            );
        }
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