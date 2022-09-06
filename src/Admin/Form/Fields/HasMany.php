<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\NestedItemRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\NestedFieldRenderer;
use Arbory\Base\Admin\Form\FieldSet;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class HasMany.
 */
class HasMany extends AbstractRelationField implements RepeatableNestedFieldInterface
{
    use HasRelationships;

    protected ?Closure $fieldSetCallback;

    /**
     * @var string
     */
    protected string $orderBy = '';

    /**
     * @var string
     */
    protected $rendererClass = NestedFieldRenderer::class;

    /**
     * @var string
     */
    protected $style = 'nested';

    /**
     * @var bool
     */
    protected bool $isSortable = false;

    /**
     * @var ItemInterface
     */
    protected $itemRenderer;

    /**
     * AbstractRelationField constructor.
     *
     * @param string $name
     * @param Closure $fieldSetCallback
     */
    public function __construct($name, Closure $fieldSetCallback)
    {
        parent::__construct($name);

        $this->fieldSetCallback = $fieldSetCallback;
        $this->itemRenderer = new NestedItemRenderer();
    }

    public function canAddRelationItem(): bool
    {
        return true;
    }

    public function canSortRelationItems(): bool
    {
        return true;
    }

    public function canRemoveRelationItems(): bool
    {
        return true;
    }

    /**
     * @param $index
     * @param Model $model
     * @return FieldSet
     */
    public function getRelationFieldSet($model, $index)
    {
        $fieldSet = new FieldSet($model, $this->getNameSpacedName() . '.' . $index);
        $fieldSetCallback = $this->fieldSetCallback;
        $fieldSetCallback($fieldSet);

        $fieldSet->prepend(
            (new Hidden($model->getKeyName()))
                ->setValue($model->getKey())
        );

        if ($this->isSortable() && $this->getOrderBy()) {
            $fieldSet->prepend(
                (new Hidden($this->getOrderBy()))
                    ->setValue($model->{$this->getOrderBy()})
            );
        }

        return $fieldSet;
    }

    public function beforeModelSave(Request $request)
    {
    }

    public function afterModelSave(Request $request)
    {
        $items = (array) $request->input($this->getNameSpacedName(), []);

        foreach ($items as $index => $item) {
            $relatedModel = $this->findRelatedModel($item);

            if (filter_var(Arr::get($item, '_destroy'), FILTER_VALIDATE_BOOLEAN)) {
                $relatedModel->delete();

                continue;
            }

            $relation = $this->getRelation();

            if ($relation instanceof MorphMany) {
                $relatedModel->fill(Arr::only($item, $relatedModel->getFillable()));
                $relatedModel->setAttribute($relation->getMorphType(), $relation->getMorphClass());
            }

            if (! $relation instanceof BelongsToMany) {
                $relatedModel->setAttribute($relation->getForeignKeyName(), $this->getModel()->getKey());
            }

            $relatedFieldSet = $this->getRelationFieldSet(
                $relatedModel,
                $index
            );

            foreach ($relatedFieldSet->getFields() as $field) {
                $field->beforeModelSave($request);
            }

            if ($relation instanceof BelongsToMany) {
                $relation->save($relatedModel);
            } else {
                $relatedModel->save();
            }

            foreach ($relatedFieldSet->getFields() as $field) {
                $field->afterModelSave($request);
            }
        }
    }

    /**
     * @param $variables
     * @return Model
     */
    private function findRelatedModel($variables)
    {
        $relation = $this->getRelation();

        $relatedModelId = Arr::get($variables, $relation->getRelated()->getKeyName());

        return $relation->getRelated()->findOrNew($relatedModelId);
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }

    /**
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return $this
     */
    public function setOrderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getRules(): array
    {
        $rules = [];

        foreach ($this->getRelationFieldSet($this->getRelatedModel(), '*')->getFields() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }

    public function getNestedFieldSet($model): FieldSet|array
    {
        return $this->getRelationFieldSet($model, 0);
    }

    /**
     * Make this field sortable.
     *
     * @return $this
     */
    public function sortable(string $field = 'position'): self
    {
        $this->isSortable = true;
        $this->setOrderBy($field);

        return $this;
    }

    /**
     * @return Renderer\RendererInterface|mixed
     */
    public function newRenderer()
    {
        return app()->makeWith(
            $this->rendererClass,
            [
                'field' => $this,
                'itemRenderer' => $this->itemRenderer,
            ]
        );
    }

    public function getItemRenderer(): ItemInterface
    {
        return $this->itemRenderer;
    }

    private function setItemRenderer(ItemInterface $itemRenderer): self
    {
        $this->itemRenderer = $itemRenderer;

        return $this;
    }
}
