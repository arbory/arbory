<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Constructor\BlockInterface;
use Arbory\Base\Admin\Form\Fields\Constructor;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Renderer\Styles\Options\StyleOptionsInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Navigator\Navigator;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

/**
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class ConstructorFieldRenderer implements RendererInterface
{
    /**
     * @var Constructor
     */
    protected $field;

    /**
     * NestedFieldRenderer constructor.
     *
     * @param Constructor $field
     */
    public function __construct(Constructor $field)
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $orderBy       = $this->field->getOrderBy();
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
        if (!$this->field->canAddRelationItem()) {
            return null;
        }

        $title = trans('arbory::fields.has_many.add_item', ['name' => $this->field->getName()]);

        $select = Html::select()->addClass('template-types');

        $select->append(Html::option('--'));

        foreach ($this->field->getTypes() as $type => $object) {
            $fieldSet = $this->field->getRelationFieldSet($this->field->buildFromBlock($object), '_template_');

            $select->append(
                Html::option($object->name())->setValue($type)->addAttributes(
                    [
                        'data-template' => $this->getRelationItemHtml($object, $fieldSet, '_template_')
                    ]
                )
            );
        }

        return Html::footer(
            Html::button(
                [
                    Html::i()->addClass('fa fa-plus'),
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
     * @param Panel   $panel
     * @param Content $content
     * @param         $name
     *
     * @return Element|null
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function addRemoveButton(Panel $panel, Content $content, $name)
    {
        if (!$this->field->canRemoveRelationItems()) {
            return null;
        }

        $button = Button::create()
                        ->title(trans('arbory::fields.relation.remove'))
                        ->type('button', 'only-icon danger remove-nested-item')
                        ->withIcon('trash-o')
                        ->iconOnly();

        $input = Html::input()
                     ->setType('hidden')
                     ->setName($name)
                     ->setValue('false')
                     ->addClass('destroy');

        $panel->addButton($button);

        $content->push($input);

        return $content;
    }

    /**
     * @param Panel $panel
     *
     * @return Element
     */
    protected function addSortableNavigation(Panel $panel)
    {
        if (!$this->field->canSortRelationItems()) {
            return null;
        }

        $panel->addButton(
            Button::create()
                  ->title(trans('arbory::fields.relation.moveDown'))
                  ->type('button', 'only-icon secondary sortable-navigation move-down')
                  ->withIcon('chevron-down')
                  ->iconOnly()
        );

        $panel->addButton(
            Button::create()
                  ->title(trans('arbory::fields.relation.moveUp'))
                  ->type('button', 'only-icon secondary sortable-navigation move-up')
                  ->withIcon('chevron-up')
                  ->iconOnly()
        );
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
        $panel = new Panel();
        $panel->setTitle($block->title());
        $panel->addClass('item type-association')
              ->addAttributes(
                  [
                      'data-title' => $block->title(),
                      'data-name'  => $this->field->getName(),
                      'data-index' => $index
                  ]
              );

        $panel->setNavigable(!in_array($index, ['*', '_template_'], true));

        if($panel->isNavigable()) {
            $item = app(Navigator::class)->addItem($panel, $block->title());

            $panel->addAttributes(['data-reference' => $item->getReference()]);
        }

        $content = new Content([
            $fieldSet->render()
        ]);

        $this->addSortableNavigation($panel);
        $this->addRemoveButton($panel, $content, $fieldSet->getNamespace() . '._destroy');

        $panel->setContent($content);

        return $panel->render();
    }

    /**
     * @return Content
     */
    public function render()
    {
        return new Content(
            [
                $this->getBody(),
//            $this->getFooter()
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
     * Configure the style before rendering the field
     *
     * @param StyleOptionsInterface $options
     *
     * @return StyleOptionsInterface
     */
    public function configure(StyleOptionsInterface $options): StyleOptionsInterface
    {
//        $options->addClass('polymorphic');

        $templates = collect();

        foreach ($this->field->getTypes() as $type => $object) {
            $fieldSet = $this->field->getRelationFieldSet($this->field->buildFromBlock($object), '_template_');

            $templates[$object->name()] = (string)$this->getRelationItemHtml($object, $fieldSet, '_template_');
        }

        $options->addAttributes(
            [
                'data-templates' => json_encode($templates->all())
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
