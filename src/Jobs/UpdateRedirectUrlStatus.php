<?php

namespace Arbory\Base\Jobs;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Arbory\Base\RedirectHealthChecker;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRedirectUrlStatus implements ShouldQueue
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var array
     */
    protected $redirectIds;

    /** @var RedirectHealthChecker */
    private $redirectHealthChecker;

    /**
     * UpdateRedirectUrlStatus constructor.
     * @param array $redirectIds
     */
    public function __construct(array $redirectIds)
    {
        $this->redirectIds = $redirectIds;
    }

    /**
     * Execute the job.
     *
     * @param Logger $logger
     * @return void
     */
    public function handle(Logger $logger)
    {
        $this->logger = $logger;

        $this->checkAndUpdateRedirectStatus();
    }

    /**
     * @return void
     */
    private function checkAndUpdateRedirectStatus()
    {
        try {
            $redirects = $this->selectRedirects($this->redirectIds);

            $redirectHealthChecker = new RedirectHealthChecker($redirects);
            $redirectHealthChecker->check();

            $this->updateStatusBulk($redirectHealthChecker->getValidIds(), true);
            $this->updateStatusBulk($redirectHealthChecker->getInvalidIds(), false);

            $this->redirectHealthChecker = $redirectHealthChecker;
        } catch (\Exception $e) {
            $this->logger->warning($e->getMessage());
        }
    }

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function selectRedirects(array $ids)
    {
        $results = DB::table('redirects')->whereIn('id', $ids)->get(['id', 'to_url']);

        return $results;
    }

    /*
     * @return void
     */
    public function updateStatusBulk(array $entryIds, int $status)
    {
        DB::table('redirects')->whereIn('id', $entryIds)->update(['status' => $status]);
    }

    /**
     * @return RedirectHealthChecker|null
     */
    public function getResult()
    {
        return $this->redirectHealthChecker ?? null;
    }
}