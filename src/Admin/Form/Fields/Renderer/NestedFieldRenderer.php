<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\NestedItemRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

/**
 * Class NestedFieldRenderer.
 */
class NestedFieldRenderer implements RendererInterface
{
    /**
     * NestedFieldRenderer constructor.
     */
    public function __construct(protected HasMany $field, protected ItemInterface $itemRenderer)
    {
    }

    protected function getBody(): Element
    {
        $orderBy = $this->field->getOrderBy();
        $relationItems = [];

        if ($orderBy) {
            $this->field->setValue($this->field->getValue()->sortBy(fn($item) => $item->{$orderBy}));
        }

        foreach ($this->field->getValue() as $index => $item) {
            $relationItems[] = $this->getRelationItemHtml(
                $this->field->getRelationFieldSet($item, $index),
                $index
            );
        }

        return Html::div($relationItems)->addClass('body list');
    }

    protected function getFooter(): ?Element
    {
        if (! $this->field->canAddRelationItem()) {
            return null;
        }

        $title = trans('arbory::fields.has_many.add_item', ['name' => $this->field->getName()]);

        return Html::footer(
            Html::button([
                Html::i('add')->addClass('mt-icon'),
                $title,
            ])
                ->addClass('button with-icon primary add-nested-item')
                ->addAttributes([
                    'type' => 'button',
                    'title' => $title,
                ])
        );
    }

    public function getItemRenderer(): ItemInterface
    {
        return $this->itemRenderer;
    }

    public function setItemRenderer(NestedItemRenderer $itemRenderer): self
    {
        $this->itemRenderer = $itemRenderer;

        return $this;
    }

    protected function getRelationItemHtml(FieldSet $fieldSet, string $index)
    {
        return $this->itemRenderer->__invoke($this->field, $fieldSet, $index);
    }

    protected function getRelationFromTemplate(): Element
    {
        $fieldSet = $this->field->getRelationFieldSet($this->field->getRelatedModel(), '_template_');

        return $this->getRelationItemHtml($fieldSet, '_template_');
    }

    public function render(): Content
    {
        return new Content([
            $this->getBody(),
            $this->getFooter(),
        ]);
    }

    public function setField(FieldInterface $field): RendererInterface
    {
        $this->field = $field;

        return $this;
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    /**
     * Configure the style before rendering the field.
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
        $options->addAttributes([
            'data-arbory-template' => $this->getRelationFromTemplate(),
        ]);

        if ($this->field->isSortable()) {
            $options->addAttributes(
                ['data-sort-by' => $this->field->getOrderBy()]
            );

            $options->addClass('type-sortable');
        }

        return $options;
    }
}
