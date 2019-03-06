<?php


namespace Arbory\Base\Admin\Form\Fields\Renderer\Nested;


use Arbory\Base\Admin\Form\Fields\Concerns\HasRenderOptions;
use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Html\Html;

class NestedItemRenderer implements ItemInterface
{
    use HasRenderOptions;

    public function __invoke(FieldInterface $field, FieldSet $fieldSet, $index = null)
    {
        $classes = implode(' ', $this->getClasses());

        $fieldSetHtml = Html::fieldset()
                            ->addClass( 'item type-association' )
                            ->addClass($classes)
                            ->addAttributes($this->getAttributes())
                            ->addAttributes( [
                                'data-name' => $field->getName(),
                                'data-index' => $index
                            ] );

        $fieldSetHtml->append($fieldSet->render());
        $fieldSetHtml->append( $this->getSortableNavigation($field) );

        $fieldSetHtml->append(
            $this->getFieldSetRemoveButton( $field, $fieldSet->getNamespace() . '._destroy' )
        );

        return $fieldSetHtml;
    }

    /**
     * @param $field
     *
     * @return \Arbory\Base\Html\Elements\Element|null
     */
    protected function getSortableNavigation($field)
    {
        if( !$field->canSortRelationItems() )
        {
            return null;
        }

        $navigation = Html::div()->addClass( 'sortable-navigation' );

        $navigation->append( Button::create()
                                   ->title( trans( 'arbory::fields.relation.moveDown' ) )
                                   ->type( 'button', 'only-icon secondary move-down' )
                                   ->withIcon( 'chevron-down' )
                                   ->iconOnly() );

        $navigation->append( Button::create()
                                   ->title( trans( 'arbory::fields.relation.moveUp' ) )
                                   ->type( 'button', 'only-icon secondary move-up' )
                                   ->withIcon( 'chevron-up' )
                                   ->iconOnly() );

        return $navigation;
    }

    /**
     * @param FieldInterface $field
     * @param                $name
     *
     * @return \Arbory\Base\Html\Elements\Element|null
     * @throws \Arbory\Base\Exceptions\BadMethodCallException
     */
    protected function getFieldSetRemoveButton( FieldInterface $field, $name )
    {
        if( !$field->canRemoveRelationItems() )
        {
            return null;
        }
        $button = Button::create()
                        ->title( trans( 'arbory::fields.relation.remove' ) )
                        ->type( 'button', 'only-icon danger remove-nested-item' )
                        ->withIcon( 'trash-o' )
                        ->iconOnly();

        $input = Html::input()
                     ->setType( 'hidden' )
                     ->setName( $name )
                     ->setValue( 'false' )
                     ->addClass( 'destroy' );

        return Html::div( [ $button, $input ] )->addClass( 'remove-item-box' );
    }
}