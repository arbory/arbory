<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Arbory\Base\Exceptions\GlobalSearchException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GlobalSearchService
{
    protected const ALLOWED_RELATIONSHIP_NESTING_LEVEL = 2;

    public function __construct(protected Admin $admin)
    {
    }

    /**
     * @throws GlobalSearchException
     */
    public function search(string $term): array
    {
        $searchableModules = $this->admin
            ->modules()
            ->all()
            ->filter(function ($module) {
                /**
                 * @var Module $module
                 */
                $configuration = $module->getConfiguration();
                $controller = app($configuration->getControllerClass());

                return property_exists($controller, 'module') &&
                    $module->isAuthorized() &&
                    property_exists($controller, 'searchBy') &&
                    ! empty($controller->searchBy);
            });

        return $searchableModules->mapWithKeys(function ($module) use ($term) {
            $configuration = $module->getConfiguration();
            $controller = app($configuration->getControllerClass());
            $searchFields = $controller->searchBy;
            $model = $controller->resource();

            $formattedResults = $this->findResults($module, $model, $searchFields, $term);
            if (! $formattedResults->count()) {
                return [];
            }

            return [ucfirst($module->name()) => $formattedResults];
        })->toArray();
    }

    protected function findResults(Module $module, Model $model, array $searchFields, string $term): Collection
    {
        $searchQuery = $model::query();

        foreach ($searchFields as $searchField) {
            $searchField = Str::of($searchField)->explode('.');

            if ($searchField->count() > self::ALLOWED_RELATIONSHIP_NESTING_LEVEL) {
                throw new GlobalSearchException('Only one level nested relationship search is allowed');
            }

            if ($searchField->count() === self::ALLOWED_RELATIONSHIP_NESTING_LEVEL) {
                $searchQuery->orWhereHas($searchField[0], function (Builder $query) use ($searchField, $term) {
                    return $query->where($searchField[1], 'LIKE', '%' . $term . '%');
                });
            } else {
                $searchQuery = $searchQuery->orWhere($searchField[0], 'LIKE', '%' . $term . '%');
            }
        }

        $results = $searchQuery->take(config('arbory.search.results_count_per_module'))->get();

        return $this->formattedResults($module, $results);
    }

    protected function formattedResults(Module $module, Collection $results): Collection
    {
        return $results->map(function ($row) use ($module) {
            return [
                'title' => Str::of((string) $row)
                    ->limit(config('arbory.search.result_title_length'))
                    ->ucfirst(),
                'url' => $module->url('edit', $row->getKey()),
            ];
        });
    }
}
