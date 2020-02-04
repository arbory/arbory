<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Filter\Repositories\SavedFilterRepository;
use Arbory\Base\Admin\Module;
use Arbory\Base\Http\Requests\FilterStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Trait FilterCrudify.
 */
trait FilterCrudify
{
    /**
     * @return View
     */
    protected function saveFilterDialog(): View
    {
        return view('arbory::dialogs.save_filter', [
            'action' => $this->url('filter.store'),
        ]);
    }

    /**
     * @param FilterStoreRequest $request
     * @return RedirectResponse
     */
    public function storeFilter(FilterStoreRequest $request): RedirectResponse
    {
        $this->savedFilterRepository()->create([
            'name' => $request->get('name'),
            'filter' => $request->get('filter'),
            'module' => $this->module()->name(),
        ]);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return
     */
    public function deleteFilterDialog(Request $request): View
    {
        $filterId = (int) $request->get('filter_id');
        $filter = $this->savedFilterRepository()->findOrFail($filterId, $this->module());

        return view('arbory::dialogs.confirm_delete', [
            'object_name' => $filter->name,
            'form_target' => $this->module()->url('filter.destroy', [$filter->id]),
            'list_url' => $this->module()->url('index'),
        ]);
    }

    /**
     * @param int $filterId
     * @return RedirectResponse
     */
    public function destroyFilter(int $filterId): RedirectResponse
    {
        $this->savedFilterRepository()
            ->findOrFail($filterId, $this->module())
            ->delete();

        return redirect()->back();
    }

    /**
     * @return SavedFilterRepository
     */
    private function savedFilterRepository(): SavedFilterRepository
    {
        return app(SavedFilterRepository::class);
    }

    /**
     * @return Module
     */
    abstract protected function module();
}
