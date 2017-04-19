<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Views;

use Cartalyst\Sentinel\Sentinel;
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
     */
    public function compose( View $view )
    {
        $view->with( 'user', $this->sentinel->getUser() );
    }
}
