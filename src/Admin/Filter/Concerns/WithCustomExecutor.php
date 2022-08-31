<?php

namespace Arbory\Base\Admin\Filter\Concerns;

use Arbory\Base\Admin\Filter\FilterItem;
use Illuminate\Database\Eloquent\Builder;

interface WithCustomExecutor
{
    public function execute(FilterItem $filterItem, Builder $builder): void;
}
