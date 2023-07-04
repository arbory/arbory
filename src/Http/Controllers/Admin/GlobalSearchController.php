<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Module;
use Arbory\Base\Support\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term');
        $results = [];

        if (!$term) {
            return response()->json(['no_results' => trans('arbory::search.no_results')]);
        }

        foreach (Admin::modules()->all() as $module) {
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

                $searchQuery = $model::query()
                    ->select(array_merge(['id'], (array) $searchFields));

                if (is_array($searchFields)) {
                    foreach ($searchFields as $index => $searchField) {
                        if ($index === 0) {
                            $searchQuery = $searchQuery->where($searchField, 'LIKE', '%' . $term . '%');
                        } else {
                            $searchQuery = $searchQuery->orWhere($searchField, 'LIKE', '%' . $term . '%');
                        }
                    }
                } else {
                    $searchQuery = $searchQuery->where((string) $searchFields, 'LIKE', '%' . $term . '%');
                }

                $foundResults = $searchQuery->take(5)->get();
                if (!$foundResults->count()) {
                    continue;
                }

                $foundResults = $foundResults->map(function ($row) use ($module) {
                    return [
                        'title' => Str::of((string)$row)->limit(20)->ucfirst(),
                        'url' => $module->url('edit', $row->getKey())
                    ];
                });

                $results[ucfirst($module->name())] = $foundResults;
            }
        }

        return response()->json(
            count($results) ? $results : ['no_results' => trans('arbory::search.no_results')]
        );
    }
}
