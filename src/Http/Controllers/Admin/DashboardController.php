<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('arbory::controllers.dashboard.index');
    }
}
