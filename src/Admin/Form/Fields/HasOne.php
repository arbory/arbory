<?php

namespace Arbory\Base\Admin\Form\Fields;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

/**
 * Class HasOne.
 */
class HasOne extends AbstractRelationField implements RenderOptionsInterface
{
    use HasRenderOptions;

    protected string $style = 'section';

    public function render(): Element
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()
            ->addClass('section content-fields')
            ->addAttributes($this->getAttributes())
            ->addClass(implode(' ', $this->getClasses()));

        $block->append(
            $this->getRelationFieldSet($item)->render()
        );

        return $block;
    }

    public function getRelationFieldSet(Model $relatedModel): FieldSet
    {
        $fieldSet = $this->getNestedFieldSet($relatedModel);

        $fieldSet->hidden($relatedModel->getKeyName())
            ->setValue($relatedModel->getKey());

        return $fieldSet;
    }

    public function beforeModelSave(Request $request): void
    {
    }

    /**
     * @throws MassAssignmentException
     */
    public function afterModelSave(Request $request)
    {
        $relatedModel = $this->getValue() ?: $this->getRelatedModel();
        $relation = $this->getRelation();

        foreach ($this->getRelationFieldSet($relatedModel)->getFields() as $field) {
            $field->beforeModelSave($request);
        }

        if ($relation instanceof MorphOne) {
            $polymorphicFields = [
                $relation->getMorphType() => $relation->getParent()::class,
                $relation->getForeignKeyName() => $relation->getParent()->{$relatedModel->getKeyName()},
            ];
            $relatedModel->fill($polymorphicFields)->save();
        } elseif ($relation instanceof MorphTo) {
            $relatedModel->save();

            $this->getModel()->fill([
                $relation->getMorphType() => $relatedModel::class,
                $relation->getForeignKeyName() => $relatedModel->{$relatedModel->getKeyName()},
            ])->save();
        } elseif ($relation instanceof BelongsTo) {
            $relatedModel->save();

            $this->getModel()->setAttribute($relation->getForeignKeyName(), $relatedModel->getKey());
            $this->getModel()->save();
        } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
            $relatedModel->setAttribute($relation->getForeignKeyName(), $this->getModel()->getKey());
            $relatedModel->save();
        }

        foreach ($this->getRelationFieldSet($relatedModel)->getFields() as $field) {
            $field->afterModelSave($request);
        }
    }

    public function getRules(): array
    {
        $rules = [];

        $relation = $this->getRelation();

        if ($relation instanceof MorphTo) {
            $model = clone $this->fieldSet->getModel();

            $str = $this->getFieldSet()->getNamespace() . '.' . $relation->getMorphType();
            $value = request()->input($str);

            // For deeply nested items if the key contains '*', request->input returns an array
            if (is_array($value)) {
                $value = head($value);
            }

            $model->setAttribute($relation->getMorphType(), $value);
            $model->setAttribute($relation->getForeignKeyName(), 0);

            $relatedModel = $model->{$this->getName()}()->getRelated();
        } else {
            $relatedModel = $this->getValue() ?: $this->getRelatedModel();
        }

        foreach ($this->getRelationFieldSet($relatedModel)->getFields() as $field) {
            $rules = array_merge($rules, $field->getRules());
        }

        return $rules;
    }
}
