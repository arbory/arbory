<?php

namespace Arbory\Base\Admin;

use Illuminate\Http\Request;
use Arbory\Base\Content\Relation;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Form\Validator;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Traits\Renderable;
use Arbory\Base\Admin\Traits\EventDispatcher;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;

/**
 * Class Form.
 */
class Form
{
    use ModuleComponent;
    use EventDispatcher;
    use Renderable;

    public const INPUT_RETURN_URL = '_return_url';

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var FieldSet
     */
    protected $fields;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var string|null
     */
    protected $returnUrl;

    /**
     * @var
     */
    protected $namespace = 'resource';

    /**
     * Form constructor.
     *
     * @param Model $model
     * @param string $namespace
     */
    public function __construct(Model $model, $namespace = 'resource')
    {
        $this->model = $model;
        $this->namespace = $namespace;
        $this->fields = new FieldSet($model, $this->namespace, app(StyleManager::class));
        $this->validator = app(Validator::class);

        $this->registerEventListeners();
    }

    /**
     * @param $title
     * @return Form
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if ($this->title === null) {
            $this->title = ($this->model->getKey())
                ? (string) $this->model
                : trans('arbory::resources.create_new');
        }

        return $this->title;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        if ($this->action) {
            return $this->action;
        }

        if ($this->getModel()->getKey()) {
            return $this->getModule()->url('update', $this->getModel()->getKey());
        }

        return $this->getModule()->url('store');
    }

    /**
     * @return FieldSet
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * @param \Closure $fieldConstructor
     * @return $this
     */
    public function setFields(\Closure $fieldConstructor)
    {
        $fieldConstructor($this->fields(), $this->getModel());

        return $this;
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->trigger('create.before', $request);

        $this->model->save();

        $this->trigger('create.after', $request);

        $this->model->push();
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $this->trigger('update.before', $request);

        $this->model->save();

        $this->trigger('update.after', $request);

        $this->model->push();
    }

    public function destroy()
    {
        $this->trigger('delete.before', $this);

        $this->model->delete();

        $this->model->morphMany(Relation::class, 'related')->get()->each(function (Relation $relation) {
            $relation->delete();
        });

        $this->trigger('delete.after', $this);
    }

    /**
     * @return Validator
     */
    public function validate()
    {
        $this->trigger('validate.before', request());

        $this->validator->setRules($this->fields()->getRules());
        $this->validator->validate($this->validator->rules());

        return $this->validator;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string|null $returnUrl
     * @return Form
     */
    public function setReturnUrl(?string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        if ($this->returnUrl) {
            return $this->returnUrl;
        }

        return request(static::INPUT_RETURN_URL);
    }

    /**
     * @return void
     */
    protected function registerEventListeners(): void
    {
        $this->addEventListeners(
            ['create.before', 'update.before'],
            function ($request) {
                foreach ($this->fields() as $field) {
                    $field->beforeModelSave($request);
                }
            }
        );

        $this->addEventListeners(
            ['create.after', 'update.after'],
            function ($request) {
                foreach ($this->fields() as $field) {
                    $field->afterModelSave($request);
                }
            }
        );
    }
}
