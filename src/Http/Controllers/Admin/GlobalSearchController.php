<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Exceptions\GlobalSearchException;
use Arbory\Base\Services\GlobalSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GlobalSearchController extends Controller
{
    public function __construct(protected GlobalSearchService $globalSearchService)
    {
    }

    /**
     * @throws GlobalSearchException
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('term');

        if (!$term) {
            return response()->json(['no_results' => trans('arbory::search.no_results')]);
        }

        $results = $this->globalSearchService->search($term);

        return response()->json(
            count($results) ? $results : ['no_results' => trans('arbory::search.no_results')]
        );
    }
}
