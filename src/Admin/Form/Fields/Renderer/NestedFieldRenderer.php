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
     * @var HasMany
     */
    protected $field;

    /**
     * @var ItemInterface
     */
    protected $itemRenderer;

    /**
     * NestedFieldRenderer constructor.
     *
     * @param HasMany       $field
     * @param ItemInterface $itemRenderer
     */
    public function __construct(HasMany $field, ItemInterface $itemRenderer)
    {
        $this->field = $field;
        $this->itemRenderer = $itemRenderer;
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $orderBy = $this->field->getOrderBy();
        $relationItems = [];

        if ($orderBy) {
            $this->field->setValue($this->field->getValue()->sortBy(function ($item) use ($orderBy) {
                return $item->{$orderBy};
            }));
        }

        foreach ($this->field->getValue() as $index => $item) {
            $relationItems[] = $this->getRelationItemHtml(
                $this->field->getRelationFieldSet($item, $index),
                $index
            );
        }

        return Html::div($relationItems)->addClass('body list');
    }

    /**
     * @return Element|null
     */
    protected function getFooter()
    {
        if (! $this->field->canAddRelationItem()) {
            return;
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

    /**
     * @return NestedItemRenderer
     */
    public function getItemRenderer(): NestedItemRenderer
    {
        return $this->itemRenderer;
    }

    /**
     * @param NestedItemRenderer $itemRenderer
     *
     * @return NestedFieldRenderer
     */
    public function setItemRenderer(NestedItemRenderer $itemRenderer): self
    {
        $this->itemRenderer = $itemRenderer;

        return $this;
    }

    /**
     * @param FieldSet $fieldSet
     * @param $index
     * @return Element
     */
    protected function getRelationItemHtml(FieldSet $fieldSet, $index)
    {
        return $this->itemRenderer->__invoke($this->field, $fieldSet, $index);
    }

    /**
     * @return Element
     */
    protected function getRelationFromTemplate()
    {
        $fieldSet = $this->field->getRelationFieldSet($this->field->getRelatedModel(), '_template_');

        return $this->getRelationItemHtml($fieldSet, '_template_');
    }

    /**
     * @return Element
     */
    public function render()
    {
        return new Content([
            $this->getBody(),
            $this->getFooter(),
        ]);
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
