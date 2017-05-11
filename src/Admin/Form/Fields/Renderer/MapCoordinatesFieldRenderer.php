<?php

namespace CubeSystems\Leaf\Admin\Form\Fields\Renderer;

use CubeSystems\Leaf\Admin\Form\Fields\MapCoordinates;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
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
        $body = Html::div();

        $body->append( Html::div()->addClass( 'canvas' ) );
        
        $field = new Text( $this->field->getName() );
        $field->setLabel( (string) null );
        $field->setFieldSet( $this->field->getFieldSet() );

        $body->append( $field->render() );

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