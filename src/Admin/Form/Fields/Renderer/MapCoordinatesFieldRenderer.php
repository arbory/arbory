<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\Hidden;
use Arbory\Base\Admin\Form\Fields\MapCoordinates;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

class MapCoordinatesFieldRenderer implements Renderable
{
    /**
     * @var MapCoordinates
     */
    protected $field;

    /**
     * @param MapCoordinates $field
     */
    public function __construct( MapCoordinates $field )
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
        $value = $this->field->getValue();
        $body = Html::div();

        $body->append( Html::div()->addClass( 'canvas' ) );
        
        $field = new Hidden( $this->field->getName() );
        $field->setFieldSet( $this->field->getFieldSet() );
        $field->setValue( is_array( $value ) ? implode( ',', $value ) : $value );
        $body->append( $field->render() );

        $field = new Text( 'search' );
        $field->setLabel( (string) null );
        $field->setFieldSet( $this->field->getFieldSet() );
        $body->append( Html::div( $field->render() )->addClass( 'search_address' ) );

        return $body->addClass( 'body' );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::section( [
            $this->getHeader(),
            $this->getBody(),
        ] )
            ->addAttributes( $this->field->getData() )
            ->addClass( 'nested' )
            ->addClass( 'coordinate_picker' )
            ->addAttributes( [
                'data-name' => $this->field->getName(),
            ] );
    }
}
