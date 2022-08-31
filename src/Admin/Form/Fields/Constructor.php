<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Constructor\Models\ConstructorBlock;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\Renderer\ConstructorFieldRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\NestedItemRenderer;
use Arbory\Base\Admin\Form\FieldSet;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LogicException;

class Constructor extends AbstractRelationField implements RepeatableNestedFieldInterface, RenderOptionsInterface
{
    use HasRenderOptions;
    use HasRelationships;

    public const BLOCK_NAME = 'name';
    public const BLOCK_CONTENT = 'content';

    /**
     * @var string
     */
    protected string $orderBy = '';

    /**
     * @var string
     */
    protected $rendererClass = ConstructorFieldRenderer::class;

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
     * @var bool
     */
    protected bool $allowToAdd = true;

    /**
     * AbstractRelationField constructor.
     *
     * @param string $name
     * @param BlockRegistry $registry
     */
    public function __construct($name, protected BlockRegistry $registry)
    {
        $this->itemRenderer = new NestedItemRenderer();

        parent::__construct($name);
    }

    /**
     * @return BlockInterface[]|Collection
     */
    public function getTypes(): array|Collection
    {
        return $this->registry->all();
    }

    /**
     * @return BlockRegistry|Application|mixed
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @return bool
     */
    public function canAddRelationItem()
    {
        return $this->allowToAdd;
    }

    /**
     * @return bool
     */
    public function canSortRelationItems()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canRemoveRelationItems()
    {
        return true;
    }

    /**
     * @param ConstructorBlock $model
     * @param  $index
     * @return FieldSet
     */
    public function getRelationFieldSet($model, $index)
    {
        $blockName = $model->name;
        $block = $this->resolveBlockByName($blockName);

        if ($block === null) {
            throw new LogicException("Block '{$blockName}' not found");
        }

        $fieldSet = new FieldSet($model, $this->getNameSpacedName() . '.' . $index);

        $fieldSet->hidden($model->getKeyName())
            ->setValue($model->getKey());

        $fieldSet->hidden(static::BLOCK_NAME)
            ->setValue($blockName);

        $fieldSet->hidden($model->content()->getMorphType())
            ->setValue($model->content()->getModel()::class);

        if ($this->isSortable() && $this->getOrderBy()) {
            $fieldSet->hidden($this->getOrderBy())
                ->setValue($model->{$this->getOrderBy()});
        }

        $fieldSet->hasOne(
            'content',
            Closure::fromCallable([$block, 'fields'])
        );

        return $fieldSet;
    }

    public function beforeModelSave(Request $request)
    {
    }

    public function afterModelSave(Request $request)
    {
        $items = (array)$request->input($this->getNameSpacedName(), []);

        foreach ($items as $index => $item) {
            $relatedModel = $this->createRelatedModelFromRequest($item);

            if (filter_var(Arr::get($item, '_destroy'), FILTER_VALIDATE_BOOLEAN)) {
                $relatedModel->delete();
                $relatedModel->content()->delete();

                continue;
            }

            $this->verifyBlockFromRequest($item, $relatedModel);
            $block = $this->resolveBlockByName(Arr::get($item, static::BLOCK_NAME));

            $relatedFieldSet = $this->getRelationFieldSet(
                $relatedModel,
                $index
            );

            foreach ($relatedFieldSet->getFields() as $field) {
                if ($this->isContentField($field)) {
                    $block->beforeModelSave($request, $field);
                } else {
                    $field->beforeModelSave($request);
                }
            }

            $relatedModel->save();

            foreach ($relatedFieldSet->getFields() as $field) {
                if ($this->isContentField($field)) {
                    $block->afterModelSave($request, $field);
                } else {
                    $field->afterModelSave($request);
                }
            }
        }
    }

    /**
     * @param $variables
     * @return ConstructorBlock
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
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return $this
     */
    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getRules(): array
    {
        $rules = [[]];

        $items = (array)request()->input($this->getNameSpacedName(), []);

        foreach ($items as $index => $item) {
            // Do not add rules for fields which will be removed
            if (filter_var(Arr::get($item, '_destroy'), FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $relatedModel = $this->createRelatedModelFromRequest($item);

            $this->verifyBlockFromRequest($item, $relatedModel);

            $relatedFieldSet = $this->getRelationFieldSet(
                $relatedModel,
                $index
            );

            foreach ($relatedFieldSet->getFields() as $field) {
                $rules[] = $field->getRules();
            }
        }

        return array_merge(...$rules);
    }

    /**
     * @param $name
     */
    public function resolveBlockByName($name): ?BlockInterface
    {
        return $this->registry->resolve($name);
    }

    public function buildFromBlock(BlockInterface $block): Model
    {
        $content = $block->resource();

        $model = $this->getRelatedModel();
        $model->name = $block->name();
        $model->content()->associate(
            new $content
        );

        return $model;
    }

    /**
     * @param $model
     */
    public function getNestedFieldSet($model): FieldSet
    {
        return $this->getRelationFieldSet($model, 0);
    }

    /**
     * Make this field sortable.
     *
     * @return $this
     */
    public function sortable(string $field = 'position')
    {
        $this->isSortable = true;
        $this->setOrderBy($field);

        return $this;
    }

    protected function isContentField(FieldInterface $field): bool
    {
        return $field instanceof HasOne && $field->getName() === 'content';
    }

    /**
     * @return bool
     */
    protected function verifyBlockFromRequest(array $item, Model $model)
    {
        $blockName = Arr::get($item, static::BLOCK_NAME);
        $blockResource = Arr::get($item, $model->content()->getMorphType());
        $block = $this->resolveBlockByName($blockName);

        if (!$block) {
            throw new LogicException("Unknown block '{$blockName}'");
        }

        if ($blockResource !== $block->resource()) {
            throw new LogicException("Invalid resource for '{$blockName}'");
        }

        return true;
    }

    /**
     * @return ConstructorBlock
     */
    protected function createRelatedModelFromRequest(array $item): Model
    {
        $relation = $this->getRelation();
        $relatedModel = $this->findRelatedModel($item);

        if (!$relation instanceof MorphMany) {
            throw new LogicException('Unknown relation used');
        }

        $relatedModel->fill(Arr::only($item, $relatedModel->getFillable()));
        $relatedModel->setAttribute($relation->getForeignKeyName(), $this->getModel()->getKey());
        $relatedModel->setAttribute($relation->getMorphType(), $relation->getMorphClass());

        return $relatedModel;
    }

    public function getItemRenderer(): ItemInterface
    {
        return $this->itemRenderer;
    }

    public function setItemRenderer(ItemInterface $itemRenderer): self
    {
        $this->itemRenderer = $itemRenderer;

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

    public function setAllowToAdd(bool $allowToAdd): self
    {
        $this->allowToAdd = $allowToAdd;

        return $this;
    }
}
