<?php

namespace CubeSystems\Leaf\Http\Controllers;

use Illuminate\Routing\Controller;

/**
 * Class DashboardController
 * @package CubeSystems\Leaf\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( )
    {
        return view('leaf::admin.controllers.dashboard.index');
    }

}
