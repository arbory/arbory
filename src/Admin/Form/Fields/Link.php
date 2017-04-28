<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Form\FieldSet;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

class Link extends HasOne
{
    /**
     * @param string $name
     */
    public function __construct( string $name )
    {
        $fieldSetCallback = function( FieldSet $fieldSet )
        {
            $fieldSet->add( new Text( 'href' ) );
            $fieldSet->add( new Text( 'title' ) );
            $fieldSet->add( new Checkbox( 'new_tab' ) );
        };

        parent::__construct( $name, $fieldSetCallback );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::section( [
            $this->getHeader(),
            $this->getBody(),
        ] )->addClass( 'nested' );
    }

    /**
     * @return Element
     */
    protected function getHeader()
    {
        return Html::header( Html::h1( trans( 'leaf::fields.link.title' ) ) );
    }

    /**
     * @return Element
     */
    protected function getBody()
    {
        $item = $this->getValue() ?: $this->getRelatedModel();

        $block = Html::div()->addClass( 'body list' );

        $fieldSetHtml = Html::fieldset()->addClass( 'item' );

        foreach( $this->getRelationFieldSet( $item )->getFields() as $field )
        {
            $fieldSetHtml->append( $field->render() );
        }

        return $block->append( $fieldSetHtml );
    }
}