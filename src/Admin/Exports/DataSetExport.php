<?php

namespace Arbory\Base\Admin\Exports;

use Illuminate\Support\Collection;

class DataSetExport extends Collection
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * DataSetExport constructor.
     * @param Collection $items
     */
    public function __construct(Collection $items)
    {
        $this->items = $items;
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
    public function setItems(Collection $items): DataSetExport
    {
        $this->items = $items;

        return $this;
    }
}
