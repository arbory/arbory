<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Views;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Menu\MenuFactory;
use CubeSystems\Leaf\Menu\MenuItemFactory;
use Illuminate\Contracts\View\View;

/**
 * Class LayoutViewComposer
 * @package CubeSystems\Leaf\Views
 */
final class LayoutViewComposer implements ViewComposer
{
    /**
     * @var Sentinel
     */
    private $sentinel;

    /**
     * LayoutViewComposer constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * @param View $view
     * @return void
     * @throws \DomainException
     */
    public function compose( View $view )
    {
        $itemFactory = \App::make( MenuItemFactory::class );
        $factory = new MenuFactory( $itemFactory );

        $view->with( 'user', $this->sentinel->getUser() );
        $view->with( 'menu', $factory->build( config( 'leaf.menu' ) )->render() );
    }
}
