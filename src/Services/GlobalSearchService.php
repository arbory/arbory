<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GlobalSearchService
{
    protected const MODULE_RESULTS_COUNT = 5;
    protected const RESULT_TITLE_LENGTH = 20;

    public function __construct(protected Admin $admin)
    {
    }

    public function search(string $term): array
    {
        $results = [];

        foreach ($this->admin->modules()->all() as $module) {
            /**
             * @var Module $module
             */
            $configuration = $module->getConfiguration();

            $controller = app($configuration->getControllerClass());
            if (!property_exists($controller, 'module')) {
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
                    if ($searchField->count() === 2) {
                        $searchQuery->orWhereHas($searchField[0], function (Builder $query) use ($searchField, $term) {
                            return $query->where($searchField[1], 'LIKE', '%' . $term . '%');
                        });
                    } else {
                        $searchQuery = $searchQuery->orWhere($searchField[0], 'LIKE', '%' . $term . '%');
                    }
                }

                $foundResults = $searchQuery->take(self::MODULE_RESULTS_COUNT)->get();
                if (!$foundResults->count()) {
                    continue;
                }

                $foundResults = $foundResults->map(function ($row) use ($module) {
                    return [
                        'title' => Str::of((string) $row)->limit(self::RESULT_TITLE_LENGTH)->ucfirst(),
                        'url' => $module->url('edit', $row->getKey()),
                    ];
                });

                $results[ucfirst($module->name())] = $foundResults;
            }
        }

        return $results;
    }
}
