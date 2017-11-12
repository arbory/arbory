<?php

namespace Arbory\Base\Console\Commands;

use Waavi\Translation\Commands\CacheFlushCommand;

class TranslationsCacheFlushCommand extends CacheFlushCommand
{
    public function handle()
    {
        return $this->fire();
    }
}
