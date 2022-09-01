<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form\FieldSet;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\Factory;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Support\Translate\Language;
use Waavi\Translation\Repositories\LanguageRepository;

class LanguageController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Language::class;

    public function __construct(protected LanguageRepository $repository)
    {
    }

    /**
     * @return Form
     */
    public function form(Form $form)
    {
        return $form->setFields(function (FieldSet $fields) {
            $fields->text('locale')->rules('required');
            $fields->text('name')->rules('required');
        });
    }

    /**
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        $grid->setColumns(function (Grid $grid) {
            $grid->column('locale');
            $grid->column('name');
            $grid->column('status')->display(fn($_, $__, Language $language) => Html::span($language->trashed() ?
                trans('arbory::resources.status.disabled') : trans('arbory::resources.status.enabled')));
        });

        return $grid
            ->items(Language::withTrashed()->get())
            ->paginate(false);
    }

    /**
     * @param  int  $resourceId
     *
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function restore($resourceId): RedirectResponse|Redirector
    {
        /** @var Language $resource */
        $resource = $this->resource()->withTrashed()->findOrFail($resourceId);

        $resource->restore();

        return redirect($this->module()->url('index'));
    }

    /**
     * @param  int  $resourceId
     *
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function disable($resourceId): RedirectResponse|Redirector
    {
        /** @var Language $resource */
        $resource = $this->resource()->findOrFail($resourceId);

        $resource->delete();

        return redirect($this->module()->url('index'));
    }

    /**
     * @param $resourceId
     */
    public function destroy($resourceId): RedirectResponse|Redirector
    {
        $resource = $this->resource()->withTrashed()->findOrFail($resourceId);

        $resource->forceDelete();

        return redirect($this->module()->url('index'));
    }

    protected function toolbox(ToolboxMenu $tools)
    {
        /** @var Language $model */
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', [$model->getKey()]));

        $disableUrl = $this->url('dialog', ['dialog' => 'confirm_disable', 'id' => $model->getKey()]);
        $restoreUrl = $this->url('dialog', ['dialog' => 'confirm_restore', 'id' => $model->getKey()]);
        $deleteUrl = $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $model->getKey()]);

        if ($model->trashed()) {
            $tools->add('restore', $restoreUrl)->dialog();
            $tools->add('delete', $deleteUrl)->dialog()->danger();
        } else {
            $tools->add('disable', $disableUrl)->dialog()->danger();
        }
    }

    protected function confirmDisableDialog(Request $request): Factory|\Illuminate\View\View
    {
        $resourceId = $request->get('id');
        $model = $this->resource()->find($resourceId);

        return view('arbory::dialogs.confirm_disable', [
            'form_target' => $this->url('disable', [$resourceId]),
            'list_url' => $this->url('index'),
            'object_name' => (string) $model,
        ]);
    }

    protected function confirmRestoreDialog(Request $request): Factory|\Illuminate\View\View
    {
        $resourceId = $request->get('id');
        $model = $this->resource()->withTrashed()->find($resourceId);

        return view('arbory::dialogs.confirm_restore', [
            'form_target' => $this->url('restore', [$resourceId]),
            'list_url' => $this->url('index'),
            'object_name' => (string) $model,
        ]);
    }
}
