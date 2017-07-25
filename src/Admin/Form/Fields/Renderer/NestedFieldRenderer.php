<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Form\Fields\HasMany;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use function foo\func;
use Illuminate\Support\Collection;

/**
 * Class NestedFieldRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class NestedFieldRenderer
{
    /**
     * @var HasMany
     */
    protected $field;

    /**
     * NestedFieldRenderer constructor.
     * @param HasMany $field
     */
    public function __construct( HasMany $field )
    {
        $this->field = $field;
    }

    /**
     * @return Element
     */
    protected function getHeader()
    {
        return Html::header( Html::h1( $this->field->getLabel() ) );
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $orderBy = $this->field->getOrderBy();
        $relationItems = [];

        if( $orderBy )
        {
            $this->field->setValue( $this->field->getValue()->sortBy( function( $item ) use ($orderBy)
            {
                return $item->{$orderBy};
            } ) );
        }

        foreach( $this->field->getValue() as $index => $item )
        {
            $relationItems[] = $this->getRelationItemHtml(
                $this->field->getRelationFieldSet( $item, $index ),
                $index
            );
        }

        return Html::div( $relationItems )->addClass( 'body list' );
    }

    /**
     * @return Element|null
     */
    protected function getFooter()
    {
        if( !$this->field->canAddRelationItem() )
        {
            return null;
        }

        $title = trans( 'arbory::fields.has_many.add_item', [ 'name' => $this->field->getName() ] );

        return Html::footer(
            Html::button( [
                Html::i()->addClass( 'fa fa-plus' ),
                $title,
            ] )
                ->addClass( 'button with-icon primary add-nested-item' )
                ->addAttributes( [
                    'type' => 'button',
                    'title' => $title,
                ] )
        );
    }

    /**
     * @param $name
     * @return Element
     */
    protected function getFieldSetRemoveButton( $name )
    {
        if( !$this->field->canRemoveRelationItems() )
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

    /**
     * @return Element
     */
    protected function getSortableNavigation()
    {
        if( !$this->field->canSortRelationItems() )
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
     * @param FieldSet $fieldSet
     * @param $index
     * @return Element
     */
    protected function getRelationItemHtml( FieldSet $fieldSet, $index )
    {
        $fieldSetHtml = Html::fieldset()
            ->addClass( 'item type-association' )
            ->addAttributes( [
                'data-name' => $this->field->getName(),
                'data-index' => $index
            ] );

        foreach( $fieldSet->getFields() as $field )
        {
            $fieldSetHtml->append( $field->render() );
        }

        $fieldSetHtml->append( $this->getSortableNavigation() );

        $fieldSetHtml->append(
            $this->getFieldSetRemoveButton( $fieldSet->getNamespace() . '._destroy' )
        );

        return $fieldSetHtml;
    }

    /**
     * @return Element
     */
    protected function getRelationFromTemplate()
    {
        $fieldSet = $this->field->getRelationFieldSet( $this->field->getRelatedModel(), '_template_' );

        return $this->getRelationItemHtml( $fieldSet, '_template_' );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::section( [
            $this->getHeader(),
            $this->getBody(),
            $this->getFooter(),
        ] )
            ->addClass( 'nested' )
            ->addAttributes( [
                'data-name' => $this->field->getName(),
                'data-arbory-template' => $this->getRelationFromTemplate(),
            ] );
    }

}
