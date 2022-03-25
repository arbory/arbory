<?php

namespace Arbory\Base\Admin\Filter\Repositories;

use Arbory\Base\Admin\Filter\Models\SavedFilter;
use Arbory\Base\Admin\Module;
use Arbory\Base\Repositories\AbstractModelsRepository;
use Illuminate\Support\Collection;

/**
 * Class SavedFilterRepository.
 */
class SavedFilterRepository extends AbstractModelsRepository
{
    /**
     * @var string
     */
    protected $modelClass = SavedFilter::class;

    /**
     * @param  Module  $module
     * @return SavedFilter[]|Collection
     */
    public function findByModule(Module $module): Collection
    {
        return $this->findBy('module', $module->name());
    }

    /**
     * @param  int  $filterId
     * @param  Module  $module
     * @return SavedFilter
     */
    public function findOrFail(int $filterId, Module $module): SavedFilter
    {
        return $this->newQuery()
            ->where('module', $module->name())
            ->findOrFail($filterId);
    }
}
