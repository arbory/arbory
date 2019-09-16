<?php

namespace Arbory\Base\Admin\Filter\Concerns;

use Arbory\Base\Admin\Filter\FilterItem;
use Illuminate\Database\Eloquent\Builder;

interface WithCustomExecutor
{
    /**
     * @param FilterItem $filterItem
     * @param Builder $builder
     * @return void
     */
    public function execute(FilterItem $filterItem, Builder $builder): void;
}
