<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Filter\FilterManager;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Filter.
 */
class Filter implements FilterInterface
{
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
    protected bool $paginated = true;

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
    protected array $defaultOrderOptions;

    /**
     * Filter constructor.
     *
     * @param Model $model
     */
    public function __construct(protected Model $model)
    {
        $this->query = $model->newQuery();
        $this->request = request();
    }

    public function order(Collection $columns): void
    {
        $orderBy = $this->request->get('_order_by');
        $orderDirection = $this->request->get('_order', 'asc');

        if (! $orderBy) {
            return;
        }

        $column = $columns->filter(fn (Column $column) => $column->isSortable())->filter(static fn (Column $column) => $column->getName() === $orderBy)->first();

        if (! $column) {
            return;
        }

        $this->query->orderBy($column->getName(), $orderDirection);
    }

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
    public function search($phrase, Collection|array $columns)
    {
        $phrase = Str::ascii($phrase);
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

    public function execute(Collection $columns): self
    {
        if ($this->request->has('search') && ! empty($this->request->get('search'))) {
            $this->search($this->request->get('search'), $columns);
        }

        $this->filter();

        $this->order($columns);

        return $this;
    }

    public function withRelation(string $relationName)
    {
        $this->query->with($relationName);
    }

    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    public function isPaginated(): bool
    {
        return $this->paginated;
    }

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

    public function getFilterTypeAction(Column $column): array
    {
        return $column->getFilterType()->getAction();
    }

    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    public function setFilterManager(FilterManager $filterManager): self
    {
        $this->filterManager = $filterManager;

        return $this;
    }

    public function getFilterManager(): ?FilterManager
    {
        return $this->filterManager;
    }

    public function getDefaultOrderOptions(): array
    {
        return $this->defaultOrderOptions;
    }

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
