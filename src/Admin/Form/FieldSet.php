<?php

namespace Arbory\Base\Admin\Form;

use Countable;
use ArrayAccess;
use Traversable;
use ArrayIterator;
use IteratorAggregate;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Services\FieldTypeRegistry;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Arbory\Base\Services\FieldSetFieldFinder;
use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Form\Fields\AbstractField;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
use Waavi\Translation\Repositories\LanguageRepository;

/**
 * Class FieldSet.
 * @method \Arbory\Base\Admin\Form\Fields\BelongsTo belongsTo(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\BelongsToMany belongsToMany(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\Checkbox checkbox(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\DateTime dateTime(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\ArboryFile file(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\HasMany hasMany(string $relationName, \Closure $fieldSetCallback)
 * @method \Arbory\Base\Admin\Form\Fields\HasOne hasOne(string $relationName, \Closure $fieldSetCallback)
 * @method \Arbory\Base\Admin\Form\Fields\Hidden hidden(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\IconPicker icon(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\ArboryImage image(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\Link link(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\MapCoordinates mapCoordinates(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\CompactRichtext markup(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\MultipleSelect multipleSelect(string $relationName)
 * @method \Arbory\Base\Admin\Form\Fields\ObjectRelation objectRelation(string $relation, $relatedModel, $limit = 0)
 * @method \Arbory\Base\Admin\Form\Fields\Password password(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\Richtext richtext(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\Select select(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\Slug slug(string $fieldName, string $fromFieldName, string $apiUrl)
 * @method \Arbory\Base\Admin\Form\Fields\Text text(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\Textarea textarea(string $fieldName)
 * @method \Arbory\Base\Admin\Form\Fields\Translatable translatable(FieldInterface $field)
 * @method \Arbory\Base\Admin\Form\Fields\Constructor constructor( string $fieldName, ?BlockRegistry $registry = null)
 */
class FieldSet implements ArrayAccess, IteratorAggregate, Countable, Arrayable, Renderable
{
    /**
     * @var FieldSetRendererInterface
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var FieldTypeRegistry
     */
    protected $fieldTypeRegister;

    /**
     * @var StyleManager
     */
    protected $styleManager;

    /**
     * @var string
     */
    protected $defaultStyle;

    /**
     * @var Collection
     */
    protected $items;

    /**
     * Resource constructor.
     *
     * @param Model $model
     * @param string $namespace
     * @param StyleManager $styleManager
     */
    public function __construct(Model $model, $namespace, StyleManager $styleManager = null)
    {
        $this->items = collect();

        if (is_null($styleManager)) {
            $styleManager = app(StyleManager::class);
        }

        $this->namespace = $namespace;
        $this->model = $model;
        $this->fieldTypeRegister = app(FieldTypeRegistry::class);
        $this->styleManager = $styleManager;
        $this->defaultStyle = $styleManager->getDefaultStyle();
        $this->renderer = new FieldSetRenderer($this, $styleManager);
    }

    /**
     * @param string $inputName
     *
     * @return AbstractField|null
     */
    public function findFieldByInputName(string $inputName)
    {
        $inputNameParts = explode('.', $inputName);
        $fields = $this->findFieldsByInputName($inputName);

        return array_get($fields, end($inputNameParts));
    }

    /**
     * @param string $inputName
     *
     * @return array
     */
    public function findFieldsByInputName(string $inputName)
    {
        return (new FieldSetFieldFinder(app(LanguageRepository::class), $this))->find($inputName);
    }

    /**
     * @param string $fieldName
     *
     * @return AbstractField|null
     */
    public function getFieldByName(string $fieldName)
    {
        return $this->getFields()->first(function (AbstractField $field) use ($fieldName) {
            return $field->getName() === $fieldName;
        });
    }

    /**
     * @param string $fieldName
     *
     * @return Collection
     */
    public function getFieldsByName(string $fieldName)
    {
        $fields = [];

        foreach ($this->getFields()->toArray() as $field) {
            /** @var AbstractField $field */
            if ($field->getName() === $fieldName) {
                $fields[] = $field;
            }
        }

        return new Collection($fields);
    }

    /**
     * @return Collection|FieldInterface[]
     */
    public function getFields()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getRules()
    {
        $rules = [];

        foreach ($this->all() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    /**
     * @param FieldInterface $field
     * @param null $key
     *
     * @return FieldSet|Collection
     */
    public function prepend($field, $key = null)
    {
        $field->setFieldSet($this);

        return $this->items->prepend($field, $key);
    }

    /**
     * @param FieldInterface $field
     *
     * @return FieldInterface
     */
    public function add(FieldInterface $field)
    {
        $field->setFieldSet($this);

        $this->items->push($field);

        return $field;
    }

    /**
     * @param string $key
     * @param FieldInterface $field
     */
    public function offsetSet($key, $field)
    {
        $field->setFieldSet($this);

        $this->items->offsetSet($key, $field);
    }

    /**
     * Renders fieldSet with defined renderer.
     *
     * @return mixed
     */
    public function render()
    {
        return $this->renderer->render();
    }

    /**
     * @return array|FieldInterface[]
     */
    public function all()
    {
        return $this->items->all();
    }

    /**
     * @return StyleManager
     */
    public function getStyleManager(): StyleManager
    {
        return $this->styleManager;
    }

    /**
     * @param StyleManager $styleManager
     *
     * @return FieldSet
     */
    public function setStyleManager(StyleManager $styleManager): self
    {
        $this->styleManager = $styleManager;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultStyle(): string
    {
        return $this->defaultStyle;
    }

    /**
     * @param string $defaultStyle
     *
     * @return FieldSet
     */
    public function setDefaultStyle(string $defaultStyle): self
    {
        $this->defaultStyle = $defaultStyle;

        return $this;
    }

    /**
     * Returns a iterator.
     *
     * @return ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->all()[$key];
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->all()[$key]);
    }

    /**
     * Counts elements.
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items->toArray();
    }

    /**
     * @return FieldSetRendererInterface
     */
    public function getRenderer(): FieldSetRendererInterface
    {
        return $this->renderer;
    }

    /**
     * @param FieldSetRendererInterface $renderer
     *
     * @return FieldSet
     */
    public function setRenderer(FieldSetRendererInterface $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return FieldInterface|mixed
     */
    public function __call($method, $parameters)
    {
        if ($this->fieldTypeRegister->has($method)) {
            return $this->add(
                $this->fieldTypeRegister->resolve($method, $parameters)
            );
        }

        return $this->items->__call($method, $parameters);
    }
}
