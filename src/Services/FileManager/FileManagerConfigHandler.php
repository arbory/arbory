<?php

namespace Arbory\Base\Services\FileManager;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class FileManagerConfigHandler extends ConfigHandler
{
    public function userField()
    {
        return Sentinel::getUser()->id;
    }
}
