<?php

namespace CubeSystems\Leaf\Admin\Widgets;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Link
 * @package CubeSystems\Leaf\Admin\Widgets
 */
class Link implements Renderable
{
    /**
     * @var $this
     */
    protected $element;

    /**
     * Link constructor.
     * @param $url
     */
    public function __construct( $url )
    {
        $this->element = Html::link()->addAttributes( [ 'href' => $url ] );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @return Link
     */
    public function title( $title )
    {
        $this->element->append( $title );
        $this->element->addAttributes( [ 'title' => $title ] );

        return $this;
    }

    /**
     * @param null $type
     * @return Link
     */
    public function asButton( $type = null )
    {
        $this->element->addClass( 'button ' . $type );

        return $this;
    }

    /**
     * @param $name
     * @return Link
     */
    public function withIcon( $name )
    {
        $this->element->addClass( 'with-icon' );
        $this->element->append( Html::i()->addClass( 'fa fa-' . $name ) );

        return $this;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return $this->element;
    }

    /**
     * @param $url
     * @return Link
     */
    public static function create( $url )
    {
        return new static( $url );
    }
}
