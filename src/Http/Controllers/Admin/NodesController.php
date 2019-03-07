<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\LayoutManager;
use Arbory\Base\Admin\Page;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Form\Fields\Deactivator;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Nodes\ContentTypeDefinition;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Nodes\Admin\Grid\Filter;
use Arbory\Base\Nodes\Admin\Grid\Renderer;
use Arbory\Base\Nodes\ContentTypeRegister;
use Arbory\Base\Repositories\NodesRepository;
use Illuminate\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NodesController extends Controller
{
    use Crudify;

    protected $resource = Node::class;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ContentTypeRegister
     */
    protected $contentTypeRegister;

    /**
     * @param Container           $container
     * @param ContentTypeRegister $contentTypeRegister
     */
    public function __construct(
        Container $container,
        ContentTypeRegister $contentTypeRegister
    ) {
        $this->container           = $container;
        $this->contentTypeRegister = $contentTypeRegister;
    }

    /**
     * @param Form            $form
     * @param LayoutInterface $panels
     *
     * @return Form
     */
    protected function form(Form $form, ?LayoutInterface $panels)
    {
        $form->setFields(function (FieldSet $fields) {
            $fields->hidden('parent_id');
            $fields->hidden('content_type');
            $fields->text('name')->rules('required');
            $fields->slug('slug', 'name', $this->getSlugGeneratorUrl())->rules('required');
            $fields->text('meta_title');
            $fields->text('meta_author');
            $fields->text('meta_keywords');
            $fields->text('meta_description');
            $fields->dateTime('activate_at');
            $fields->dateTime('expire_at')->rules('nullable|after_or_equal:resource.activate_at');

            if ($fields->getModel()->active) {
                $fields->add(new Deactivator('deactivate'));
            }

            $fields->hasOne('content', function (FieldSet $fieldSet) {

                $content = $fieldSet->getModel();

                $class      = (new \ReflectionClass($content))->getName();
                $definition = $this->contentTypeRegister->findByModelClass($class);

                $definition->getFieldSetHandler()->call($content, $fieldSet);
            });
        });

        $form->addEventListeners(['create.after'], function () use ($form) {
            $this->afterSave($form);
        });

        return $form;
    }

    /**
     * @param Grid $grid
     *
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        $grid->setColumns(function (Grid $grid) {
            $grid->column('name');
        });
        $grid->setFilter(new Filter($this->resource()));
        $grid->setRenderer(new Renderer($grid));

        return $grid;
    }

    /**
     * @param \Arbory\Base\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox(ToolboxMenu $tools)
    {
        $node = $tools->model();

        $tools->add('add_child',
            $this->url('dialog', ['dialog' => 'content_types', 'parent_id' => $node->getKey()]))->dialog();
        $tools->add('delete',
            $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $node->getKey()]))->danger()->dialog();
    }

    /**
     * @param Request       $request
     * @param LayoutManager $manager
     *
     * @return RedirectResponse|Layout
     */
    public function create(Request $request, LayoutManager $manager)
    {
        $contentType = $request->get('content_type');

        if (! $this->contentTypeRegister->isValidContentType($contentType)) {
            return redirect($this->url('index'))->withErrors('Undefined content type "' . $contentType . '"');
        }

        $node = $this->resource();
        $node->setAttribute('content_type', $contentType);
        $node->setAttribute('content_id', 0);

        if ($request->has('parent_id')) {
            $node->setAttribute($node->getParentColumnName(), $request->get('parent_id'));
        }

        $layout = $this->layout('form');
        $layout->setForm($this->buildForm($node, $layout));

        $page = $manager->page(Page::class);
        $page->use($layout);

        $page->bodyClass('controller-' . str_slug($this->module()->name()) . ' view-edit');

        return $page;
    }

    /**
     * @param Form $form
     */
    protected function afterSave(Form $form)
    {
        /**
         * @var $node Node
         */
        $node = $form->getModel();

        $parentId = $node->getAttribute($node->getParentColumnName());

        if ($parentId) {
            $parent = $node->find($parentId);
            $node->makeChildOf($parent);

            return;
        }

        $node->makeRoot();
    }

    /**
     * @return Node
     */
    public function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function contentTypesDialog(Request $request)
    {
        $contentTypes = $this->contentTypeRegister->getAllowedChildTypes(
            $this->resource()->findOrNew($request->get('parent_id'))
        );

        $types = $contentTypes->sort()->map(function (ContentTypeDefinition $definition, string $type) use ($request) {
            return [
                'title' => $definition->getName(),
                'url'   => $this->url('create', [
                    'content_type' => $type,
                    'parent_id'    => $request->get('parent_id'),
                ]),
            ];
        });

        return view('arbory::dialogs.content_types', ['types' => $types]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function nodeRepositionApi(Request $request)
    {
        /**
         * @var NodesRepository $nodes
         * @var Node            $node
         */
        $nodes     = new NodesRepository;
        $node      = $nodes->findOneBy('id', $request->input('id'));
        $toLeftId  = $request->input('toLeftId');
        $toRightId = $request->input('toRightId');

        if ($toLeftId) {
            $node->moveToRightOf($nodes->findOneBy('id', $toLeftId));
        } elseif ($toRightId) {
            $node->moveToLeftOf($nodes->findOneBy('id', $toRightId));
        }

        return \Response::make();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function slugGeneratorApi(Request $request)
    {
        $reservedSlugs = [];

        if ($request->has('parent_id')) {
            $reservedSlugs = $this->resource()
                                  ->where([
                                      ['parent_id', $request->get('parent_id')],
                                      ['id', '<>', $request->get('object_id')],
                                  ])
                                  ->pluck('slug')
                                  ->toArray();
        }

        $from = $request->get('from');
        $slug = str_slug($from);

        if (in_array($slug, $reservedSlugs, true) && $request->has('id')) {
            $slug = str_slug($request->get('id') . '-' . $from);
        }

        if (in_array($slug, $reservedSlugs, true)) {
            $slug = str_slug($from . '-' . random_int(0, 9999));
        }

        return $slug;
    }

    /**
     * @return string
     */
    protected function getSlugGeneratorUrl()
    {
        return $this->url('api', 'slug_generator');
    }
}
