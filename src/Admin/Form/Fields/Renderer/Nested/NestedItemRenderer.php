<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer\Nested;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Exceptions\BadMethodCallException;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;

class NestedItemRenderer implements ItemInterface
{
    use HasRenderOptions;

    /**
     * @param FieldInterface $field
     * @param FieldSet $fieldSet
     * @param null $index
     * @param array $parameters
     *
     * @throws BadMethodCallException
     */
    public function __invoke(FieldInterface $field, FieldSet $fieldSet, $index = null, array $parameters = []): Element
    {
        $title = $parameters['title'] ?? null;
        $classes = implode(' ', $this->getClasses());

        $fieldSetHtml = Html::fieldset()
            ->addClass('item type-association')
            ->addClass($classes)
            ->addAttributes($this->getAttributes())
            ->addAttributes([
                'data-name' => $field->getName(),
                'data-index' => $index,
            ]);

        if ($title) {
            $fieldSetHtml->addClass('with-title');
            $fieldSetHtml->append(Html::header($title));
        }

        $fieldSetHtml->append($fieldSet->render());
        $fieldSetHtml->append($this->getSortableNavigation($field));

        $fieldSetHtml->append(
            $this->getFieldSetRemoveButton($field, $fieldSet->getNamespace() . '._destroy')
        );

        return $fieldSetHtml;
    }

    protected function getSortableNavigation(FieldInterface $field): ?Element
    {
        if (!$field->canSortRelationItems()) {
            return null;
        }

        $navigation = Html::div()->addClass('sortable-navigation');

        $navigation->append(Button::create()
            ->title(trans('arbory::fields.relation.moveDown'))
            ->type('button', 'only-icon secondary move-down')
            ->withIcon('keyboard_arrow_down')
            ->iconOnly());

        $navigation->append(Button::create()
            ->title(trans('arbory::fields.relation.moveUp'))
            ->type('button', 'only-icon secondary move-up')
            ->withIcon('keyboard_arrow_up')
            ->iconOnly());

        return $navigation;
    }

    /**
     * @throws BadMethodCallException
     */
    protected function getFieldSetRemoveButton(FieldInterface $field, string $name)
    {
        if (!$field->canRemoveRelationItems()) {
            return '';
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

        return Html::div([$button, $input])->addClass('remove-item-box');
    }
}
