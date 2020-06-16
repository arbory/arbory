<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Nested;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Panels\Panel;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;

class PaneledItemRenderer implements ItemInterface
{
    use HasRenderOptions;

    public function __invoke(FieldInterface $field, FieldSet $fieldSet, $index = null, array $parameters = [])
    {
        $title = $parameters['title'] ?? '';

        $panel = new Panel();
        $panel->setTitle($title);
        $panel->addClass('item type-association')
              ->addAttributes(
                  [
                      'data-title' => $title,
                      'data-name'  => $field->getName(),
                      'data-index' => $index,
                  ]
              );

        $content = new Content([
            $fieldSet->render(),
        ]);

        $this->addSortableNavigation($field, $panel);
        $this->addRemoveButton($field, $panel, $content, $fieldSet->getNamespace().'._destroy');

        $panel->setContent($content);

        return $panel->render();
    }

    /**
     * @param FieldInterface $field
     * @param Panel          $panel
     * @param Content        $content
     * @param                $name
     *
     * @return Element|null
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function addRemoveButton(FieldInterface $field, Panel $panel, Content $content, $name)
    {
        if (! $field->canRemoveRelationItems()) {
            return;
        }

        $button = Button::create()
                        ->title(trans('arbory::fields.relation.remove'))
                        ->type('button', 'only-icon danger remove-nested-item')
                        ->withIcon('delete_outline')
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
     * @param FieldInterface $field
     * @param Panel          $panel
     *
     * @return Element
     */
    protected function addSortableNavigation(FieldInterface $field, Panel $panel)
    {
        if (! $field->canSortRelationItems()) {
            return;
        }

        $panel->addButton(
            Button::create()
                  ->title(trans('arbory::fields.relation.moveDown'))
                  ->type('button', 'only-icon secondary sortable-navigation move-down')
                  ->withIcon('keyboard_arrow_down')
                  ->iconOnly()
        );

        $panel->addButton(
            Button::create()
                  ->title(trans('arbory::fields.relation.moveUp'))
                  ->type('button', 'only-icon secondary sortable-navigation move-up')
                  ->withIcon('keyboard_arrow_up')
                  ->iconOnly()
        );
    }
}
