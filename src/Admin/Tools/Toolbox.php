<?php

namespace Arbory\Base\Admin\Tools;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Toolbox
 * @package Arbory\Base\Admin\Widgets
 */
class Toolbox implements Renderable
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var ToolboxMenu
     */
    protected $menu;

    /**
     * Toolbox constructor.
     *
     * @param string $url
     * @param ToolboxMenu|null $menu
     */
    public function __construct( $url, ToolboxMenu $menu = null )
    {
        $this->url = $url;
        $this->menu = $menu;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $attributes = [];

        if($this->url) {
            $attributes['data-url'] = $this->url;
        }

        return Html::div(
            Html::div( [
                Html::button( Html::i()->addClass( 'fa fa-ellipsis-v' ) )
                    ->addClass( 'button trigger only-icon' )
                    ->addAttributes( [ 'type' => 'button' ] ),
                Html::menu( [
                    Html::i()->addClass( 'fa fa-caret-up' ),
                    Html::ul($this->menu),
                ] )
                    ->addClass( 'toolbox-items' )
                    ->addAttributes( [ 'type' => 'toolbar' ] )
            ] )
                ->addClass( 'toolbox' )
                ->addAttributes($attributes)
        )->addClass( 'only-icon toolbox-cell' );
    }

    /**
     * @param $url
     * @return Toolbox
     */
    public static function create( $url )
    {
        return new static( $url );
    }
}
