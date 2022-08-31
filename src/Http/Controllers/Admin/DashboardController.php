<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('arbory.admin_switched_off_module');
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('arbory::controllers.dashboard.index');
    }
}
