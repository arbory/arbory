<?php

namespace Arbory\Base\Admin\Grid;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Filter\FilterManager;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Class Filter.
 */
class Filter implements FilterInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var QueryBuilder
     */
    protected $query;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $paginated = true;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var array
     */
    protected $defaultOrderOptions;

    /**
     * Filter constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
        $this->request = request();
    }

    /**
     * @param Collection|Column[] $columns
     * @return void
     */
    public function order(Collection $columns)
    {
        $orderBy = $this->request->get('_order_by');
        $orderDirection = $this->request->get('_order', 'asc');

        if (! $orderBy) {
            return;
        }

        $column = $columns->filter(function (Column $column) {
            return $column->isSortable();
        })->filter(static function (Column $column) use ($orderBy) {
            return $column->getName() === $orderBy;
        })->first();

        if (! $column) {
            return;
        }

        $this->query->orderBy($column->getName(), $orderDirection);
    }

    /**
     * @return void
     */
    public function filter(): void
    {
        if ($filterManager = $this->getFilterManager()) {
            $filterManager->apply($this->query);
        }
    }

    /**
     * @param $phrase
     * @param Collection|Column[] $columns
     */
    public function search($phrase, $columns)
    {
        $keywords = explode(' ', $phrase);

        foreach ($keywords as $string) {
            $this->query->where(function (QueryBuilder $query) use ($string, $columns) {
                foreach ($columns as $column) {
                    if (! $column->isSearchable()) {
                        continue;
                    }

                    $column->searchConditions($query, $string);
                }
            });
        }
    }

    /**
     * @return QueryBuilder|QueryBuilder[]|\Illuminate\Database\Eloquent\Collection|LengthAwarePaginator|mixed
     */
    public function loadItems()
    {
        $result = $this->query;

        if (! $this->isPaginated()) {
            return $result->get();
        }

        /** @var LengthAwarePaginator $result */
        $result = $this->query->paginate($this->getPerPage());

        if ($this->request->has('search')) {
            $result->appends([
                'search' => $this->request->get('search'),
            ]);
        }

        if ($this->request->has('_order_by') && $this->request->has('_order')) {
            $result->appends([
                '_order_by' => $this->request->get('_order_by'),
                '_order' => $this->request->get('_order'),
            ]);
        }

        return $result;
    }

    /**
     * @param Collection $columns
     * @return self
     */
    public function execute(Collection $columns): self
    {
        if ($this->request->has('search') && ! empty($this->request->get('search'))) {
            $this->search($this->request->get('search'), $columns);
        }

        $this->filter();

        $this->order($columns);

        return $this;
    }

    /**
     * @param string $relationName
     */
    public function withRelation(string $relationName)
    {
        $this->query->with($relationName);
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function isPaginated(): bool
    {
        return $this->paginated;
    }

    /**
     * @param bool $paginated
     */
    public function setPaginated(bool $paginated)
    {
        $this->paginated = $paginated;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage ?? config('arbory.pagination.items_per_page');
    }

    /**
     * @param Column $column
     * @return array
     */
    public function getFilterTypeAction(Column $column): array
    {
        return $column->getFilterType()->getAction();
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @param FilterManager $filterManager
     * @return Filter
     */
    public function setFilterManager(FilterManager $filterManager): self
    {
        $this->filterManager = $filterManager;

        return $this;
    }

    /**
     * @return FilterManager|null
     */
    public function getFilterManager(): ?FilterManager
    {
        return $this->filterManager;
    }

    /**
     * @return array
     */
    public function getDefaultOrderOptions(): array
    {
        return $this->defaultOrderOptions;
    }

    /**
     * @param string $orderBy
     * @param string $orderDirection
     * @return Filter
     */
    public function setDefaultOrderBy(string $orderBy, string $orderDirection = 'desc'): self
    {
        $this->defaultOrderOptions = [$orderBy, $orderDirection];

        $isOrderBySpecified = $this->request->get('_order_by');

        if (! $isOrderBySpecified) {
            $this->request->merge([
                '_order_by' => $orderBy,
                '_order' => $orderDirection,
            ]);
        }

        return $this;
    }
}
