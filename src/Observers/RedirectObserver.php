<?php

namespace Arbory\Base\Observers;

use Arbory\Base\Pages\Redirect;
use Arbory\Base\Jobs\UpdateRedirectUrlStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RedirectObserver
{
    use DispatchesJobs;

    /**
     * Handle the redirect "saved" event.
     *
     * @param Redirect $redirect
     * @return void
     */
    public function saved(Redirect $redirect)
    {
        $job = new UpdateRedirectUrlStatus([$redirect->id]);
        $this->dispatchNow($job);
    }
}
