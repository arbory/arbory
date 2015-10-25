<?php

namespace CubeSystems\Leaf\Http\Controllers;

class DashboardController extends Controller
{
    public function getIndexPage( )
    {
        return view('leaf::admin.controllers.dashboard.index');
    }

}
