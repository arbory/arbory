<?php

namespace Arbory\Base\Admin\Exports;

use Illuminate\Support\Collection;

class DataSetExport
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var array
     */
    protected $columns;

    /**
     * DataSetExport constructor.
     * @param Collection $items
     * @param array $columns
     */
    public function __construct(Collection $items, array $columns)
    {
        $this->items = $items;
        $this->columns = $columns;
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Collection $items
     * @return DataSetExport
     */
    public function setItems(Collection $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return DataSetExport
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }
}
