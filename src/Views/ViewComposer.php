<?php declare( strict_types=1 );

namespace Arbory\Base\Views;

use Illuminate\Contracts\View\View;

/**
 * Interface ViewComposer
 * @package Arbory\Base\Views
 */
interface ViewComposer
{
    /**
     * @param View $view
     * @return void
     */
    public function compose( View $view );
}
