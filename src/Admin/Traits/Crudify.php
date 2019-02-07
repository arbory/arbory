<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Grid\ExportBuilder;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Arbory\Base\Admin\Exports\DataSetExport;
use Arbory\Base\Admin\Exports\ExportInterface;
use Arbory\Base\Admin\Exports\Type\ExcelExport;
use Arbory\Base\Admin\Exports\Type\JsonExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait Crudify
{
    /**
     * @var array
     */
    protected static $exportTypes = [
        'xls' => ExcelExport::class,
        'json' => JsonExport::class,
    ];

    /**
     * @var Module
     */
    protected $module;

    /**
     * @return Model|Builder
     */
    public function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @return Module
     */
    protected function module()
    {
        if ($this->module === null) {
            $this->module = \Admin::modules()->findModuleByControllerClass(get_class($this));
        }

        return $this->module;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        return $form;
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function buildForm(Model $model)
    {
        $form = new Form($model);
        $form->setModule($this->module());
        $form->setRenderer(new Form\Builder($form));

        return $this->form($form) ?: $form;
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid;
    }

    /**
     * @param Model $model
     * @return Grid
     */
    protected function buildGrid(Model $model)
    {
        $grid = new Grid($model);
        $grid->setModule($this->module());
        $grid->setRenderer(new Grid\Builder($grid));

        return $this->grid($grid) ?: $grid;
    }

    /**
     * @return Layout
     */
    public function index()
    {
        $layout = new Layout(function (Layout $layout) {
            $layout->body($this->buildGrid($this->resource()));
        });

        $layout->bodyClass('controller-' . str_slug($this->module()->name()) . ' view-index');

        return $layout;
    }

    /**
     * @param int $resourceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(int $resourceId)
    {
        return redirect($this->module()->url('edit', $resourceId));
    }

    /**
     * @return Layout
     */
    public function create()
    {
        $layout = new Layout(function (Layout $layout) {
            $layout->body($this->buildForm($this->resource()));
        });

        $layout->bodyClass('controller-' . str_slug($this->module()->name()) . ' view-edit');

        return $layout;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $form = $this->buildForm($this->resource());

        $request->request->add(['fields' => $form->fields()]);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->store($request);

        return $this->getAfterEditResponse($request);
    }

    /**
     * @param int $resourceId
     * @return Layout
     */
    public function edit(int $resourceId)
    {
        $resource = $this->findOrNew($resourceId);

        $layout = new Layout(function (Layout $layout) use ($resource) {
            $layout->body($this->buildForm($resource));
        });

        $layout->bodyClass('controller-' . str_slug($this->module()->name()) . ' view-edit');

        return $layout;
    }

    /**
     * @param Request $request
     * @param int $resourceId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, int $resourceId)
    {
        $resource = $this->findOrNew($resourceId);
        $form = $this->buildForm($resource);

        $request->request->add(['fields' => $form->fields()]);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->update($request);

        return $this->getAfterEditResponse($request);
    }

    /**
     * @param int $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $resourceId)
    {
        $resource = $this->resource()->findOrFail($resourceId);

        $this->buildForm($resource)->destroy();

        return redirect($this->module()->url('index'));
    }

    /**
     * @param Request $request
     * @param string $name
     * @return mixed
     */
    public function dialog(Request $request, string $name)
    {
        $method = camel_case($name) . 'Dialog';

        if (!$name || !method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);

            return null;
        }

        return $this->{$method}($request);
    }


    /**
     * @param string $type
     * @return BinaryFileResponse
     * @throws \Exception
     */
    public function export(string $type): BinaryFileResponse
    {
        $grid = $this->buildGrid($this->resource());
        $grid->setRenderer(new ExportBuilder($grid));
        $grid->paginate(false);

        /** @var DataSetExport $dataSet */
        $dataSet = $grid->render();

        $exporter = $this->getExporter($type, $dataSet);

        return $exporter->download($this->module()->name());
    }

    /**
     * @param string $type
     * @param DataSetExport $dataSet
     * @return ExportInterface
     * @throws \Exception
     */
    protected function getExporter(string $type, DataSetExport $dataSet): ExportInterface
    {
        if (! isset(self::$exportTypes[$type])) {
            throw new \Exception('Export Type not found - ' . $type);
        }

        return new self::$exportTypes[$type]($dataSet);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function toolboxDialog(Request $request): string
    {
        $node = $this->findOrNew($request->get('id'));

        $toolbox = new ToolboxMenu($node);

        $this->toolbox($toolbox);

        return $toolbox->render();
    }

    /**
     * @param \Arbory\Base\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox(ToolboxMenu $tools)
    {
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', $model->getKey()));
        $tools->add('delete',
            $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $model->getKey()])
        )->dialog()->danger();
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    protected function confirmDeleteDialog(Request $request)
    {
        $resourceId = $request->get('id');
        $model = $this->resource()->find($resourceId);

        return view('arbory::dialogs.confirm_delete', [
            'form_target' => $this->url('destroy', [$resourceId]),
            'list_url' => $this->url('index'),
            'object_name' => (string)$model,
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return null
     */
    public function api(Request $request, string $name)
    {
        $method = camel_case($name) . 'Api';

        if (!$name || !method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);

            return null;
        }

        return $this->{$method}($request);
    }

    /**
     * @param string $route
     * @param array $parameters
     * @return string
     */
    public function url(string $route, $parameters = [])
    {
        return $this->module()->url($route, $parameters);
    }

    /**
     * @param int $resourceId
     * @return Model
     */
    protected function findOrNew(int $resourceId): Model
    {
        /**
         * @var Model $resource
         */
        $resource = $this->resource();

        if (method_exists($resource, 'bootSoftDeletes')) {
            $resource = $resource->withTrashed();
        }

        $resource = $resource->findOrNew($resourceId);
        $resource->setAttribute($resource->getKeyName(), $resourceId);

        return $resource;
    }

    /**
     * @param Request $request
     * @return array|Request|string
     */
    public function slugGeneratorApi(Request $request)
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $slug = str_slug($request->input('from'));
        $column = $request->input('column_name');

        $query = \DB::table($request->input('model_table'))->where($column, $slug);

        if ($locale = $request->input('locale')) {
            $query->where('locale', $locale);
        }

        if ($objectId = $request->input('object_id')) {
            $query->where('id', '<>', $objectId);
        }

        if ($column && $query->exists()) {
            $slug .= '-' . random_int(0, 9999);
        }

        return $slug;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getAfterEditResponse(Request $request)
    {
        return redirect($request->has('save_and_return') ? $this->module()->url('index') : $request->url());
    }
}
