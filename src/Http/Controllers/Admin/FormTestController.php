<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use App\NewsArticle;
use CubeSystems\Leaf\Admin\Module;
use CubeSystems\Leaf\Services\ModuleRegistry;

class FormTestController
{
    protected $resource = NewsArticle::class;

    public function __construct( ModuleRegistry $modules )
    {
        $this->module = $modules->findModuleByController( $this );
    }

    public function index()
    {

    }

    public function resource()
    {

    }

}


class AdminForm
{
    public function __construct( Model $resource, OLDModule $module )
    {

    }
}
