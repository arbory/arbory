<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\ObjectRelation;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class ObjectRelationRenderer implements RendererInterface
{
    /**
     * @var ObjectRelation
     */
    protected $field;

    /**
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $limit = $this->field->getLimit();

        $attributes = [
            'data-limit' => $limit,
        ];

        $contents = Html::div($this->getAvailableRelationElement())->addClass('contents');
        $relatedItemsElement = $this->getRelatedItemsElement();
        $block = Html::div()->addClass('object-relation');

        if ($this->field->hasIndentation()) {
            $attributes += ['data-indent' => $this->field->getIndentAttribute()];
        }

        if ($this->field->isSingular()) {
            $contents->append($relatedItemsElement);
        } else {
            $contents->prepend($relatedItemsElement);
        }

        $block->append($this->field->getInnerFieldSet()->render());

        if ($this->field->isSingular()) {
            $block->append($contents);

            return $block->addAttributes($attributes);
        }

        $block->append($contents);

        return $block->addAttributes($attributes);
    }

    /**
     * @return Element
     */
    protected function getRelatedItemsElement()
    {
        $items = [];
        $values = $this->field->getValue();

        if ($values) {
            $values = $values instanceof Collection ? $values : new Collection([$values]);

            foreach ($values as $value) {
                $relation = $value->related()->first();

                if ($relation) {
                    $items[] = $this->buildRelationalItemElement($relation);
                }
            }
        }

        return Html::div($items)->addClass('related');
    }

    /**
     * @return Element
     */
    protected function getAvailableRelationElement()
    {
        return Html::div($this->getAvailableRelationalItemsElement())->addClass('relations');
    }

    /**
     * @return array
     */
    protected function getAvailableRelationalItemsElement()
    {
        $items = [];
        $relational = $this->field->getOptions();

        $relational = $this->field->isSingular() ? $relational->prepend('', '') : $relational;

        foreach ($relational as $relation) {
            if ($relation instanceof Model) {
                $items[] = $this->buildRelationalItemElement($relation, $this->field->hasRelationWith($relation));
            } else {
                $items[] = $this->buildRelationalItemElement($relation);
            }
        }

        return $items;
    }

    /**
     * @param mixed $value
     * @param bool $isRelated
     * @return Element
     */
    protected function buildRelationalItemElement($value, bool $isRelated = false)
    {
        $element = Html::div(
            Html::span(
                (string) $value
            )->addClass('title')
        )->addClass('item');

        if ($value instanceof Model) {
            $element->addAttributes([
                'data-key' => $value->getKey(),
                'data-level' => $value->getAttributeValue($this->field->getIndentAttribute()),
                'data-inactive' => $isRelated && $this->field->hasIndentation() ? 'true' : 'false',
            ]);
        }

        return $element;
    }

    /**
     * @param FieldInterface $field
     *
     * @return mixed
     */
    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return FieldInterface
     */
    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        if (! $this->field->isInteractive() || $this->field->isDisabled()) {
            $options->addClass('disabled');
        } else {
            $options->addClass('interactive');
        }

        return $options;
    }
}
