<?php

namespace Arbory\Base\Services\FileManager;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler;

class FileManagerConfigHandler extends ConfigHandler
{
    public function userField()
    {
        return \Sentinel::getUser()->id;
    }
}
