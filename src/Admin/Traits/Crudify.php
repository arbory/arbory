<?php

namespace Arbory\Base\Admin\Traits;

use Admin;
use Arbory\Base\Admin\Filter\FilterManager;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Page;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Grid\ExportBuilder;
use Illuminate\Database\Eloquent\Builder;
use Arbory\Base\Admin\Exports\DataSetExport;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Exports\ExportInterface;
use Arbory\Base\Admin\Exports\Type\JsonExport;
use Arbory\Base\Admin\Exports\Type\ExcelExport;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait Crudify
{
    /**
     * @var array
     */
    protected static array $exportTypes = [
        'xls' => ExcelExport::class,
        'json' => JsonExport::class,
    ];

    /**
     * @var Module
     */
    protected $module;

    public function resource(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $class = $this->resource;

        return new $class;
    }

    protected function module(): Module
    {
        if ($this->module === null) {
            $this->module = Admin::modules()->findModuleByControllerClass($this::class);
        }

        return $this->module;
    }

    protected function form(Form $form, ?Layout\FormLayoutInterface $layout = null): Form
    {
        return $form;
    }

    /**
     * @throws Exception
     */
    protected function buildForm(Model $model, ?Layout\FormLayoutInterface $layout = null): Form
    {
        $form = new Form($model);
        $form->setModule($this->module());
        $form->setRenderer(new Form\Builder($form));

        $layout?->setForm($form);

        return $this->form($form, $layout) ?: $form;
    }

    public function grid(Grid $grid): Grid
    {
        return $grid;
    }

    protected function buildGrid(Model $model): Grid
    {
        $grid = new Grid($model);
        $grid->setModule($this->module());
        $grid->setRenderer(new Grid\Builder($grid));
        $grid->setFilterManager(app(FilterManager::class));
        $grid->setupFilter();

        return $this->grid($grid) ?: $grid;
    }

    public function index(Layout\LayoutManager $manager): Layout
    {
        $layout = $this->layout('grid');

        $layout->setGrid($this->buildGrid($this->resource()));

        $page = $manager->page(Page::class);

        $grid = $layout->getGrid();

        $bulkEditClass = $grid->hasTool('bulk-edit') ? ' bulk-edit-grid' : '';

        $page->setBreadcrumbs($this->module()->breadcrumbs());
        $page->use($layout);
        $page->bodyClass('controller-'.Str::slug($this->module()->name()).' view-index'.$bulkEditClass);

        return $page;
    }

    /**
     * @param $resourceId
     */
    public function show($resourceId): RedirectResponse
    {
        return redirect($this->module()->url('edit', $resourceId));
    }

    /**
     * @throws Exception
     */
    public function create(Layout\LayoutManager $manager): Layout
    {
        $layout = $this->layout('form');
        $form = $this->buildForm($this->resource(), $layout);

        $page = $manager->page(Page::class);

        $page->use($layout);
        $page->bodyClass('controller-'.Str::slug($this->module()->name()).' view-edit');

        return $page;
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        $layout = $this->layout('form');
        $form = $this->buildForm($this->resource(), $layout);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->store($request);

        return $this->getAfterCreateResponse($request, $form->getModel());
    }

    /**
     * @param  $resourceId
     * @return Layout
     */
    public function edit($resourceId, Layout\LayoutManager $manager)
    {
        $resource = $this->findOrNew($resourceId);
        $layout = $this->layout('form');
        $form = $this->buildForm($resource, $layout);

        $page = $manager->page(Page::class);
        $page->use($layout);
        $page->bodyClass('controller-'.Str::slug($this->module()->name()).' view-edit');

        return $page;
    }

    /**
     * @param $resourceId
     */
    public function update(Request $request, $resourceId): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $resource = $this->findOrNew($resourceId);
        $layout = $this->layout('form');
        $form = $this->buildForm($resource, $layout);

        $layout->setForm($form);

        $form->validate();

        if ($request->ajax()) {
            return response()->json(['ok']);
        }

        $form->update($request);

        return $this->getAfterEditResponse($request, $form->getModel());
    }

    /**
     * @param $resourceId
     */
    public function destroy($resourceId): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $resource = $this->resource()->findOrFail($resourceId);
        $layout = $this->layout('form');

        $this->buildForm($resource, $layout)->destroy();

        return redirect($this->module()->url('index'));
    }

    /**
     * @return mixed
     */
    public function dialog(Request $request, string $name)
    {
        $method = Str::camel($name).'Dialog';

        if (! $name || ! method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);
        }

        return $this->{$method}($request);
    }

    /**
     *
     * @throws Exception
     */
    public function export(string $type): BinaryFileResponse
    {
        $grid = $this->buildGrid($this->resource());
        $grid->setRenderer(new ExportBuilder($grid));
        $grid->paginate(false);

        $grid->exportEnabled();

        /** @var DataSetExport $dataSet */
        $dataSet = $grid->render();

        $exporter = $this->getExporter($type, $dataSet);

        return $exporter->download($this->module()->name());
    }

    /**
     *
     * @throws Exception
     */
    protected function getExporter(string $type, DataSetExport $dataSet): ExportInterface
    {
        if (! isset(self::$exportTypes[$type])) {
            throw new Exception('Export Type not found - '.$type);
        }

        return new self::$exportTypes[$type]($dataSet);
    }

    protected function toolboxDialog(Request $request): string
    {
        $node = $this->findOrNew($request->get('id'));

        $toolbox = new ToolboxMenu($node);

        $this->toolbox($toolbox);

        return $toolbox->render();
    }

    protected function toolbox(ToolboxMenu $tools)
    {
        $model = $tools->model();

        $tools->add('edit', $this->url('edit', $model->getKey()));
        $tools->add(
            'delete',
            $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $model->getKey()])
        )->dialog()->danger();
    }

    /**
     * @return View
     */
    protected function confirmDeleteDialog(Request $request)
    {
        $resourceId = $request->get('id');
        $model = $this->resource()->find($resourceId);

        return view('arbory::dialogs.confirm_delete', [
            'form_target' => $this->url('destroy', [$resourceId]),
            'list_url' => $this->url('index'),
            'object_name' => (string) $model,
        ]);
    }

    /**
     * @return null
     */
    public function api(Request $request, string $name)
    {
        $method = Str::camel($name).'Api';

        if (! $name || ! method_exists($this, $method)) {
            app()->abort(Response::HTTP_NOT_FOUND);

            return;
        }

        return $this->{$method}($request);
    }

    /**
     * @param  array  $parameters
     * @return string
     */
    public function url(string $route, $parameters = [])
    {
        return $this->module()->url($route, $parameters);
    }

    protected function findOrNew(mixed $resourceId): Model
    {
        /**
         * @var Model
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
     * @return string
     * @throws Exception
     */
    public function slugGeneratorApi(Request $request)
    {
        /** @var \Illuminate\Database\Query\Builder $query */
        $slug = Str::slug($request->input('from'));
        $column = $request->input('column_name');

        $query = DB::table($request->input('model_table'))->where($column, $slug);

        if ($locale = $request->input('locale')) {
            $query->where('locale', $locale);
        }

        if ($objectId = $request->input('object_id')) {
            $query->where('id', '<>', $objectId);
        }

        if ($column && $query->exists()) {
            $slug .= '-'.random_int(0, 9999);
        }

        return $slug;
    }

    /**
     * @param  Model  $model
     */
    protected function getAfterEditResponse(Request $request, $model): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $defaultReturnUrl = $this->module()->url('index');
        $returnUrl = $request->has(Form::INPUT_RETURN_URL) ? $request->get(Form::INPUT_RETURN_URL) : $defaultReturnUrl;

        return redirect($request->has('save_and_return') ? $returnUrl : $request->url());
    }

    /**
     * @param  Model  $model
     */
    protected function getAfterCreateResponse(Request $request, $model): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $defaultReturnUrl = $this->module()->url('index');
        $returnUrl = $request->has(Form::INPUT_RETURN_URL) ? $request->get(Form::INPUT_RETURN_URL) : $defaultReturnUrl;

        $url = $this->url('edit', $model);

        return redirect($request->has('save_and_return') ? $returnUrl : $url);
    }

    /**
     * Creates a layout instance.
     *
     * @param  string  $component
     * @return LayoutInterface
     */
    protected function layout($component, mixed $with = null)
    {
        $layouts = $this->layouts() ?: [];

        $class = $layouts[$component] ?? null;

        if (! $class && ! class_exists($class)) {
            throw new RuntimeException("Layout class '{$class}' for '{$component}' does not exist");
        }

        return $with ? app()->makeWith($class, $with) : app()->make($class);
    }

    /**
     * Defined layouts.
     *
     * @return array
     */
    public function layouts()
    {
        return [
            'grid' => Grid\Layout::class,
            'form' => Form\Layout::class,
        ];
    }
}
