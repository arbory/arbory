<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\Constructor;
use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\ItemInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Nested\PaneledItemRenderer;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;

class ConstructorFieldRenderer implements RendererInterface
{
    /**
     * @var Constructor
     */
    protected $field;
    /**
     * @var PaneledItemRenderer
     */
    protected $itemRenderer;

    /**
     * NestedFieldRenderer constructor.
     *
     * @param Constructor   $field
     * @param ItemInterface $itemRenderer
     */
    public function __construct(Constructor $field, ItemInterface $itemRenderer)
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
            $this->field->setValue(
                $this->field->getValue()->sortBy(
                    function ($item) use ($orderBy) {
                        return $item->{$orderBy};
                    }
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

    /**
     * @return Element|null
     */
    protected function getFooter()
    {
        if (! $this->field->canAddRelationItem()) {
            return;
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
                        'type'  => 'button',
                        'title' => $title,
                    ]
                )
        )->append($select);
    }

    /**
     * @param BlockInterface $block
     * @param FieldSet       $fieldSet
     * @param                $index
     *
     * @return Panel|string
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function getRelationItemHtml(BlockInterface $block, FieldSet $fieldSet, $index)
    {
        return $this->itemRenderer->__invoke($this->field, $fieldSet, $index, [
            'title' => $block->title(),
        ]);
    }

    /**
     * @return Content
     */
    public function render()
    {
        return new Content(
            [
                $this->getBody(),
                $this->getFooter(),
            ]
        );
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
        $options->addAttributes($this->field->getAttributes());
        $options->addClass(implode(' ', $this->field->getClasses()));

        $options->addClass('polymorphic');

        $templates = collect();

        foreach ($this->field->getTypes() as $type => $object) {
            $fieldSet = $this->field->getRelationFieldSet($this->field->buildFromBlock($object), '_template_');

            $templates[$object->name()] = (string) $this->getRelationItemHtml($object, $fieldSet, '_template_');
        }

        $options->addAttributes(
            [
                'data-templates' => json_encode($templates->all()),
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
