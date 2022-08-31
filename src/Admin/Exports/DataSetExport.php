<?php

namespace Arbory\Base\Admin\Exports;

use Illuminate\Support\Collection;

class DataSetExport
{
    /**
     * DataSetExport constructor.
     */
    public function __construct(protected Collection $items, protected array $columns)
    {
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }
}
