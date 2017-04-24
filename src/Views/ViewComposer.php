<?php declare( strict_types=1 );

namespace CubeSystems\Leaf\Views;

use Illuminate\Contracts\View\View;

/**
 * Interface ViewComposer
 * @package CubeSystems\Leaf\Views
 */
interface ViewComposer
{
    /**
     * @param View $view
     * @return void
     */
    public function compose( View $view );
}
