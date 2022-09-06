<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Grid\Column;
use Arbory\Base\Admin\Grid\Filter;
use Arbory\Base\Admin\Grid\FilterInterface;
use Arbory\Base\Admin\Grid\Row;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Html\Elements\Content;
use Closure;
use Illuminate\Contracts\Support\Renderable as RenderableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class Grid.
 */
class Grid
{
    use ModuleComponent;
    use Renderable;

    public const FOOTER_SIDE_PRIMARY = 'primary';
    public const FOOTER_SIDE_SECONDARY = 'secondary';

    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Collection
     */
    protected $rows;

    /**
     * @var array
     */
    protected array $enabledDefaultTools = ['create', 'search'];

    /**
     * @var array
     */
    protected array $tools = [];

    /**
     * @var Collection|null
     */
    protected $items;

    /**
     * @var bool
     */
    protected bool $paginated = true;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var callable
     */
    protected $rowUrlCallback;

    /**
     * @var callable
     */
    protected $orderUrlCallback;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var bool
     */
    protected bool $isExportEnabled = false;

    /**
     * @var bool
     */
    protected bool $rememberFilters = false;

    /**
     * @var bool
     */
    protected bool $hasToolbox = true;

    public function __construct(protected Model $model)
    {
        $this->columns = new Collection();
        $this->rows = new Collection();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @return $this
     */
    public function setColumns(Closure $constructor): self
    {
        $constructor($this);

        return $this;
    }

    /**
     * @return void
     */
    public function setupFilter()
    {
        $filter = new Filter($this->model);
        $filter->setFilterManager($this->getFilterManager());

        $this->setFilter($filter);
    }

    /**
     * @return Grid
     */
    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param string|null $side
     * @return void
     */
    public function addTool(RenderableInterface $tool, string $side = null)
    {
        $this->tools[] = [$tool, $side ?: self::FOOTER_SIDE_SECONDARY];
    }

    public function showToolbox(): self
    {
        $this->hasToolbox = true;

        return $this;
    }

    public function hideToolbox(): self
    {
        $this->hasToolbox = false;

        return $this;
    }

    public function isToolboxEnable(): bool
    {
        return $this->hasToolbox;
    }

    /**
     * @return array
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @param string[] $tools
     * @return Grid
     */
    public function tools(array $tools)
    {
        $this->enabledDefaultTools = $tools;

        return $this;
    }

    /**
     * @return Grid
     */
    public function items(array|Collection $items)
    {
        if (is_array($items)) {
            $items = new Collection($items);
        }

        $this->items = $items;

        return $this;
    }

    /**
     * @return LengthAwarePaginator|Collection|null
     */
    public function getItems()
    {
        if ($this->items === null) {
            $this->items = $this->fetchData();
        }

        return $this->items;
    }

    /**
     * @return Grid
     */
    public function paginate(bool $paginate = true)
    {
        $this->paginated = $paginate;

        return $this;
    }

    /**
     * @return Collection|Column[]
     */
    public function getColumns(): Collection|array
    {
        return $this->columns;
    }

    /**
     * @return Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param string|null $name
     * @param string|null $label
     */
    public function column($name = null, $label = null): Column
    {
        return $this->appendColumn($name, $label);
    }

    /**
     * @param string|null $name
     * @param string|null $label
     */
    public function appendColumn($name = null, $label = null): Column
    {
        $column = $this->createColumn($name, $label);
        $this->columns->push($column);

        $this->setColumnRelation($column, $name);

        return $column;
    }

    /**
     * @param string|null $name
     * @param string|null $label
     */
    public function prependColumn($name = null, $label = null): Column
    {
        $column = $this->createColumn($name, $label);
        $this->columns->prepend($column);

        $this->setColumnRelation($column, $name);

        return $column;
    }

    /**
     * @param string $column
     * @param string $name
     * @return mixed
     */
    protected function setColumnRelation($column, $name): Column
    {
        if (str_contains($name, '.')) {
            [$relationName, $relationColumn] = explode('.', $name);

            $this->filter->withRelation($relationName);
            $column->setRelation($relationName, $relationColumn);
        }

        return $column;
    }

    /**
     * @param string|null $name
     * @param string|null $label
     */
    protected function createColumn($name = null, $label = null): Column
    {
        $column = new Column($name, $label);
        $column->setGrid($this);

        return $column;
    }

    protected function buildRows(Collection|LengthAwarePaginator $items)
    {
        if ($items instanceof LengthAwarePaginator) {
            $items = new Collection($items->items());
        }

        $this->rows = $items->map(fn ($model) => new Row($this, $model));
    }

    public function filter(Closure $callback)
    {
        call_user_func($callback, $this->filter);
    }

    protected function fetchData(): LengthAwarePaginator|Collection
    {
        if (method_exists($this->filter, 'setPaginated')) {
            $this->filter->setPaginated($this->paginated);
        }

        return $this->filter->execute($this->getColumns())->loadItems();
    }

    /**
     * @return Content
     */
    public function render()
    {
        $this->buildRows($this->getItems());

        return $this->renderer->render();
    }

    /**
     * @return string[]
     */
    public function getEnabledDefaultTools(): array
    {
        return $this->enabledDefaultTools;
    }

    public function isPaginated(): bool
    {
        return $this->paginated;
    }

    public function hasTools(): bool
    {
        return ! empty($this->enabledDefaultTools);
    }

    public function hasTool(string $tool): bool
    {
        return in_array($tool, $this->enabledDefaultTools, false);
    }

    public function getRowUrl(Model $model): ?string
    {
        $filterParameters = $this->getFilterParameters();
        $params = [];

        if ($customUrlOpener = $this->getRowUrlCallback()) {
            return $customUrlOpener($model, $this, $filterParameters);
        }

        if ($this->rememberFilters()) {
            $params = [
                Form::INPUT_RETURN_URL => $this->getModule()->url('index', $filterParameters),
            ];
        }

        if ($this->hasTool('create')) {
            return $this->getModule()->url('edit', [$model->getKey()] + $params);
        }

        return null;
    }

    public function getRowUrlCallback(): ?callable
    {
        return $this->rowUrlCallback;
    }

    public function setRowUrlCallback(callable $rowUrlCallback): self
    {
        $this->rowUrlCallback = $rowUrlCallback;

        return $this;
    }

    public function getColumnOrderUrl(Column $column): ?string
    {
        $params = $this->getFilterParameters();
        $params['_order_by'] = $column->getName();
        $params['_order'] = Arr::get($params, '_order') === 'ASC' ? 'DESC' : 'ASC';

        if ($callback = $this->getOrderUrlCallback()) {
            return $callback($column, $this, $params);
        }

        return $this->getModule()->url('index', $params);
    }

    public function getOrderUrlCallback(): ?callable
    {
        return $this->orderUrlCallback;
    }

    public function setOrderUrlCallback(callable $orderUrlCallback): self
    {
        $this->orderUrlCallback = $orderUrlCallback;
    }

    public function isExportEnabled(): bool
    {
        return $this->isExportEnabled;
    }

    /**
     * @return $this
     */
    public function exportEnabled(): self
    {
        $this->isExportEnabled = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function exportDisabled(): self
    {
        $this->isExportEnabled = false;

        return $this;
    }

    public function toArray(): array
    {
        $items = $this->fetchData();

        $this->buildRows($items);

        $columns = $this->columns->map(fn (Column $column) => (string) $column)->toArray();

        return $this->rows->map(fn (Row $row) => array_combine($columns, $row->toArray()))->toArray();
    }

    public function getFilterManager(): FilterManager
    {
        return $this->filterManager;
    }

    public function setFilterManager(FilterManager $filterManager): self
    {
        $filterManager->setModule($this->getModule());
        $this->filterManager = $filterManager;

        return $this;
    }

    public function setRememberFilters(bool $rememberFilters): self
    {
        $this->rememberFilters = $rememberFilters;

        return $this;
    }

    public function rememberFilters(): bool
    {
        return $this->rememberFilters;
    }

    protected function getFilterParameters(): ?array
    {
        $filterParameters = $this->getFilterManager()->getParameters();
        $params = request()->only(['search', '_order', '_order_by']);

        if (! $filterParameters->isEmpty()) {
            $params[$filterParameters->getNamespace()] = $filterParameters->toArray();
        }

        return $params;
    }
}
