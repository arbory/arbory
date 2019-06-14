<?php


namespace Arbory\Base\Admin\Filter\Concerns;


use Illuminate\Database\Query\Builder;

interface WithCustomExecutor
{
    public function execute(Builder $builder);
}