<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Illuminate\Contracts\Routing\ResponseFactory;
use Closure;
use ReflectionClass;
use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\Fields\Deactivator;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Layout\LayoutInterface;
use Arbory\Base\Admin\Layout\LayoutManager;
use Arbory\Base\Admin\Page;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Nodes\Admin\Grid\Filter;
use Arbory\Base\Nodes\Admin\Grid\Renderer;
use Arbory\Base\Nodes\ContentTypeDefinition;
use Arbory\Base\Nodes\ContentTypeRegister;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Repositories\NodesRepository;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Arbory\Base\Support\Nodes\NameGenerator;
use Illuminate\View\View;

class NodesController extends Controller
{
    use Crudify;

    protected $resource = Node::class;

    public function __construct(protected Container $container, protected ContentTypeRegister $contentTypeRegister)
    {
    }

    /**
     * @param  LayoutInterface  $layout
     * @return Form
     */
    protected function form(Form $form, ?LayoutInterface $layout)
    {
        $definition = $this->resolveContentDefinition($form);

        $definition->getLayoutHandler()->call($this, $form, $layout);

        $form->setFields(function (FieldSet $fields) use ($layout, $definition) {
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

            $fields->hasOne('content', $this->contentResolver($definition, $layout));
        });

        /**
         * @var Node
         */
        $node = $form->fields()->getModel();
        $contentType = $node->getContentType();

        if ($contentType) {
            $form->title(sprintf('%s (%s)', $form->getTitle(), $this->makeNameFromType($contentType)));
        }

        $form->addEventListeners(['create.after'], function () use ($form) {
            $this->afterSave($form);
        });

        return $form;
    }

    /**
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

    protected function toolbox(ToolboxMenu $tools)
    {
        $node = $tools->model();

        $tools->add(
            'add_child',
            $this->url('dialog', ['dialog' => 'content_types', 'parent_id' => $node->getKey()])
        )->dialog();
        $tools->add(
            'delete',
            $this->url('dialog', ['dialog' => 'confirm_delete', 'id' => $node->getKey()])
        )->danger()->dialog();
    }

    public function create(Request $request, LayoutManager $manager): RedirectResponse|Layout
    {
        $contentType = $request->get('content_type');

        if (! $this->contentTypeRegister->isValidContentType($contentType)) {
            return redirect($this->url('index'))->withErrors('Undefined content type "'.$contentType.'"');
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
        $page->bodyClass('controller-'.Str::slug($this->module()->name()).' view-edit');

        return $page;
    }

    protected function afterSave(Form $form)
    {
        /**
         * @var Node
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
     * @return \Illuminate\View\View
     */
    public function contentTypesDialog(Request $request)
    {
        $contentTypes = $this->contentTypeRegister->getAllowedChildTypes(
            $this->resource()->findOrNew($request->get('parent_id'))
        );

        $types = $contentTypes->sort()->map(fn(ContentTypeDefinition $definition, string $type) => [
            'title' => $definition->getName(),
            'url'   => $this->url('create', [
                'content_type' => $type,
                'parent_id'    => $request->get('parent_id'),
            ]),
        ]);

        return view('arbory::dialogs.content_types', ['types' => $types]);
    }

    protected function nodeRepositionApi(Request $request): ResponseFactory|\Symfony\Component\HttpFoundation\Response
    {
        /**
         * @var NodesRepository
         * @var Node $node
         */
        $nodes = new NodesRepository;
        $node = $nodes->findOneBy('id', $request->input('id'));
        $toLeftId = $request->input('toLeftId');
        $toRightId = $request->input('toRightId');

        if ($toLeftId) {
            $node->moveToRightOf($nodes->findOneBy('id', $toLeftId));
        } elseif ($toRightId) {
            $node->moveToLeftOf($nodes->findOneBy('id', $toRightId));
        }

        return response()->make();
    }

    /**
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
        $slug = Str::slug($from);

        if (in_array($slug, $reservedSlugs, true) && $request->has('id')) {
            $slug = Str::slug($request->get('id').'-'.$from);
        }

        if (in_array($slug, $reservedSlugs, true)) {
            $slug = Str::slug($from.'-'.random_int(0, 9999));
        }

        return $slug;
    }

    protected function getSlugGeneratorUrl(): string
    {
        return $this->url('api', ['slug_generator']);
    }

    protected function constructorTypesDialog(Request $request): View
    {
        return view('arbory::dialogs.constructor_types', [
            'types' => app(BlockRegistry::class)->all(),
            'field' => $request->get('field'),
        ]);
    }

    /**
     * Creates a closure for content field.
     *
     * @return Closure
     */
    protected function contentResolver(ContentTypeDefinition $definition, ?LayoutInterface $layout)
    {
        return static function (FieldSet $fieldSet) use ($layout, $definition) {
            $content = $fieldSet->getModel();

            $definition->getFieldSetHandler()->call($content, $fieldSet, $layout);
        };
    }

    /**
     * Resolves content type based on the current model & form data.
     */
    protected function resolveContentDefinition(Form $form): ContentTypeDefinition
    {
        $model = $form->getModel();
        $morphType = $model->content()->getMorphType();
        // Find content type from model
        $attribute = $model->getAttribute($morphType);

        // Find it from request otherwise
        if ($attribute === null) {
            $attribute = \request()->input("{$form->getNamespace()}.{$morphType}");
        }

        $class = ( new ReflectionClass($attribute) )->getName();

        return $this->contentTypeRegister->findByModelClass($class);
    }

    protected function makeNameFromType(string $type): string
    {
        return $this->container->get(NameGenerator::class)->generate($type);
    }
}
