<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Form\Fields\AbstractField;
use Arbory\Base\Admin\Form\Fields\ArboryFile;
use Arbory\Base\Admin\Form\Fields\ArboryImage;
use Arbory\Base\Admin\Form\Fields\BelongsTo;
use Arbory\Base\Admin\Form\Fields\BelongsToMany;
use Arbory\Base\Admin\Form\Fields\Checkbox;
use Arbory\Base\Admin\Form\Fields\CompactRichtext;
use Arbory\Base\Admin\Form\Fields\Constructor;
use Arbory\Base\Admin\Form\Fields\DateTime;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\HasOne;
use Arbory\Base\Admin\Form\Fields\Hidden;
use Arbory\Base\Admin\Form\Fields\IconPicker;
use Arbory\Base\Admin\Form\Fields\Link;
use Arbory\Base\Admin\Form\Fields\MapCoordinates;
use Arbory\Base\Admin\Form\Fields\MultipleSelect;
use Arbory\Base\Admin\Form\Fields\ObjectRelation;
use Arbory\Base\Admin\Form\Fields\Password;
use Arbory\Base\Admin\Form\Fields\Richtext;
use Arbory\Base\Admin\Form\Fields\Select;
use Arbory\Base\Admin\Form\Fields\Slug;
use Arbory\Base\Admin\Form\Fields\Styles\StyleManager;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Form\Fields\Textarea;
use Arbory\Base\Admin\Form\Fields\Translatable;
use Arbory\Base\Services\FieldSetFieldFinder;
use Arbory\Base\Services\FieldTypeRegistry;
use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;
use Waavi\Translation\Repositories\LanguageRepository;

/**
 * Class FieldSet.
 *
 * @method BelongsTo belongsTo(string $relationName)
 * @method BelongsToMany belongsToMany(string $relationName)
 * @method Checkbox checkbox(string $fieldName)
 * @method DateTime dateTime(string $fieldName)
 * @method ArboryFile file(string $relationName)
 * @method HasMany hasMany(string $relationName, Closure $fieldSetCallback)
 * @method HasOne hasOne(string $relationName, Closure $fieldSetCallback)
 * @method Hidden hidden(string $fieldName)
 * @method IconPicker icon(string $fieldName)
 * @method ArboryImage image(string $relationName)
 * @method Link link(string $relationName)
 * @method MapCoordinates mapCoordinates(string $relationName)
 * @method CompactRichtext markup(string $fieldName)
 * @method MultipleSelect multipleSelect(string $relationName)
 * @method ObjectRelation objectRelation(string $relation, $relatedModel, $limit = 0)
 * @method Password password(string $fieldName)
 * @method Richtext richtext(string $fieldName)
 * @method Select select(string $fieldName)
 * @method Slug slug(string $fieldName, string $fromFieldName, string $apiUrl)
 * @method Text text(string $fieldName)
 * @method Textarea textarea(string $fieldName)
 * @method Translatable translatable(FieldInterface $field)
 * @method Constructor constructor(string $fieldName, ?BlockRegistry $registry = null)
 */
class FieldSet implements ArrayAccess, IteratorAggregate, Countable, Arrayable, Renderable
{
    /**
     * @var FieldSetRendererInterface
     */
    protected $renderer;

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
     */
    public function __construct(protected Model $model, protected string $namespace, StyleManager $styleManager = null)
    {
        $this->items = collect();

        if (is_null($styleManager)) {
            $styleManager = app(StyleManager::class);
        }
        $this->fieldTypeRegister = app(FieldTypeRegistry::class);
        $this->styleManager = $styleManager;
        $this->defaultStyle = $styleManager->getDefaultStyle();
        $this->renderer = new FieldSetRenderer($this, $styleManager);
    }

    public function findFieldByInputName(string $inputName): ?AbstractField
    {
        $inputNameParts = explode('.', $inputName);
        $fields = $this->findFieldsByInputName($inputName);

        return Arr::get($fields, end($inputNameParts));
    }

    public function findFieldsByInputName(string $inputName): array
    {
        return (new FieldSetFieldFinder(app(LanguageRepository::class), $this))->find($inputName);
    }

    public function getFieldByName(string $fieldName): ?AbstractField
    {
        return $this->getFields()->first(fn (AbstractField $field) => $field->getName() === $fieldName);
    }

    public function getFieldsByName(string $fieldName): Collection
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

    public function getFields(): Collection|array
    {
        return $this->items;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getRules(): array
    {
        $rules = [];

        foreach ($this->all() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    public function prepend(FieldInterface $field, string|int $key = null): Collection
    {
        $field->setFieldSet($this);

        $parameters = array_filter([$field, $key], fn ($item) => ! is_null($item));

        return $this->items->prepend(...$parameters);
    }

    public function add(FieldInterface $field): FieldInterface
    {
        $field->setFieldSet($this);

        $this->items->push($field);

        return $field;
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value): void
    {
        $value->setFieldSet($this);

        $this->items->offsetSet($key, $value);
    }

    /**
     * Renders fieldSet with defined renderer.
     */
    public function render(): mixed
    {
        return $this->renderer->render();
    }

    public function all(): array
    {
        return $this->items->all();
    }

    public function getStyleManager(): StyleManager
    {
        return $this->styleManager;
    }

    public function setStyleManager(StyleManager $styleManager): self
    {
        $this->styleManager = $styleManager;

        return $this;
    }

    public function getDefaultStyle(): string
    {
        return $this->defaultStyle;
    }

    public function setDefaultStyle(string $defaultStyle): self
    {
        $this->defaultStyle = $defaultStyle;

        return $this;
    }

    /**
     * Returns a iterator.
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->all());
    }

    /**
     * Determine if an item exists at an offset.
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->all());
    }

    /**
     * Get an item at a given offset.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Unset the item at a given offset.
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Counts elements.
     */
    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return $this->items->toArray();
    }

    public function getRenderer(): FieldSetRendererInterface
    {
        return $this->renderer;
    }

    public function setRenderer(FieldSetRendererInterface $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return FieldInterface|mixed
     */
    public function __call(string $method, array $parameters)
    {
        if ($this->fieldTypeRegister->has($method)) {
            return $this->add(
                $this->fieldTypeRegister->resolve($method, $parameters)
            );
        }

        return $this->items->__call($method, $parameters);
    }
}
