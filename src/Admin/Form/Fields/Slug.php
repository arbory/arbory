<?php

namespace CubeSystems\Leaf\Admin\Form\Fields;

use CubeSystems\Leaf\Admin\Widgets\Button;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

/**
 * Class Slug
 * @package CubeSystems\Leaf\Admin\Form\Fields
 */
class Slug extends AbstractField
{
    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * Slug constructor.
     * @param string $name
     * @param string $apiUrl
     */
    public function __construct( $name, $apiUrl )
    {
        $this->apiUrl = $apiUrl;

        parent::__construct( $name );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $baseUrl = url( '/' );
        $uri = $this->getModel()->getUri();

        $label = Html::label( $this->getLabel() )->addAttributes( [ 'for' => $this->getNameSpacedName() ] );

        $input = Html::input()
            ->setName( $this->getNameSpacedName() )
            ->setValue( $this->getValue() )
            ->addClass( 'text' )
            ->addAttributes( [ 'data-generator-url' => $this->apiUrl ] );

        $button = Button::create()
            ->type( 'button', 'secondary generate' )
            ->title( trans( 'leaf::fields.slug.suggest_slug' ) )
            ->withIcon( 'keyboard-o' )
            ->iconOnly()
            ->render();

        return Html::div( [
            Html::div( $label )->addClass( 'label-wrap' ),
            Html::div( [ $input, $button ] )->addClass( 'value' ),
            Html::div( Html::link( [ $baseUrl . '/' . Html::span( $uri ) ] ) )->addClass( 'link' ),
        ] )->addClass( 'field type-text' )->addAttributes( [ 'data-name' => 'slug' ] );
    }

}
