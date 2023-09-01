<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Arbory\Base\Exceptions\GlobalSearchException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GlobalSearchService
{
    public function __construct(protected Admin $admin)
    {
    }

    /**
     * @throws GlobalSearchException
     */
    public function search(string $term): array
    {
        $results = [];

        foreach ($this->admin->modules()->all() as $module) {
            /**
             * @var Module $module
             */
            $configuration = $module->getConfiguration();

            $controller = app($configuration->getControllerClass());
            if (!property_exists($controller, 'module') || !$module->isAuthorized()) {
                continue;
            }

            /**
             * @var Model $model
             */
            $model = $controller->resource();

            if (property_exists($controller, 'searchBy') && !empty($controller->searchBy)) {
                $searchFields = $controller->searchBy;

                $searchQuery = $model::query();

                foreach ($searchFields as $searchField) {
                    $searchField = Str::of($searchField)->explode('.');

                    if ($searchField->count() > 2) {
                        throw new GlobalSearchException('Only one level nested relationship search is allowed');
                    }

                    if ($searchField->count() === 2) {
                        $searchQuery->orWhereHas($searchField[0], function (Builder $query) use ($searchField, $term) {
                            return $query->where($searchField[1], 'LIKE', '%' . $term . '%');
                        });
                    } else {
                        $searchQuery = $searchQuery->orWhere($searchField[0], 'LIKE', '%' . $term . '%');
                    }
                }

                $foundResults = $searchQuery->take(config('arbory.search.results_count_per_module', 5))
                    ->get();
                if (!$foundResults->count()) {
                    continue;
                }

                $foundResults = $foundResults->map(function ($row) use ($module) {
                    return [
                        'title' => Str::of((string) $row)
                            ->limit(config('arbory.search.result_title_length', 20))
                            ->ucfirst(),
                        'url' => $module->url('edit', $row->getKey()),
                    ];
                });

                $results[ucfirst($module->name())] = $foundResults;
            }
        }

        return $results;
    }
}
