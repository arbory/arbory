<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Form\Fields\Constructor;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Exceptions\BadMethodCallException;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class ConstructorFieldRenderer implements RendererInterface
{
    /**
     * NestedFieldRenderer constructor.
     */
    public function __construct(protected Constructor $field, protected ItemInterface $itemRenderer)
    {
    }

    protected function getBody(): Element
    {
        $orderBy = $this->field->getOrderBy();
        $relationItems = [];

        if ($orderBy) {
            $this->field->setValue(
                $this->field->getValue()->sortBy(
                    fn ($item) => $item->{$orderBy}
                )
            );
        }

        foreach ($this->field->getValue() as $index => $item) {
            $block = $this->field->resolveBlockByName($item->name);

            $relationItems[] = $this->getRelationItemHtml(
                $block,
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

        $select = Html::select()->addClass('template-types');

        $select->append(Html::option('--'));

        foreach ($this->field->getTypes() as $type => $object) {
            $fieldSet = $this->field->getRelationFieldSet($this->field->buildFromBlock($object), '_template_');

            $select->append(
                Html::option($object->title())->setValue($type)->addAttributes(
                    [
                        'data-template' => $this->getRelationItemHtml($object, $fieldSet, '_template_'),
                    ]
                )
            );
        }

        return Html::footer(
            Html::button(
                [
                    Html::i('add')->addClass('mt-icon'),
                    $title,
                ]
            )
                ->addClass('button with-icon primary add-nested-item')
                ->addAttributes(
                    [
                        'type' => 'button',
                        'title' => $title,
                    ]
                )
        )->append($select);
    }

    /**
     * @throws BadMethodCallException
     */
    protected function getRelationItemHtml(BlockInterface $block, FieldSet $fieldSet, string $index): Element
    {
        return $this->itemRenderer->__invoke($this->field, $fieldSet, $index, [
            'title' => $block->title(),
        ]);
    }

    public function render(): Content
    {
        return new Content(
            [
                $this->getBody(),
                $this->getFooter(),
            ]
        );
    }

    /**
     * @return mixed
     */
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
        $options->addAttributes($this->field->getAttributes());
        $options->addClass(implode(' ', $this->field->getClasses()));

        $options->addClass('polymorphic');

        $templates = collect();

        foreach ($this->field->getTypes() as $object) {
            $fieldSet = $this->field->getRelationFieldSet($this->field->buildFromBlock($object), '_template_');

            $templates[$object->name()] = (string) $this->getRelationItemHtml($object, $fieldSet, '_template_');
        }

        $options->addAttributes(
            [
                'data-templates' => json_encode($templates->all(), JSON_THROW_ON_ERROR),
                'data-namespaced-name' => $this->field->getNameSpacedName(),
            ]
        );

        if ($this->field->isSortable()) {
            $options->addAttributes(
                ['data-sort-by' => $this->field->getOrderBy()]
            );

            $options->addClass('type-sortable');
        }

        return $options;
    }
}
