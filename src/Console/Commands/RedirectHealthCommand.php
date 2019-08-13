<?php

namespace Arbory\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Arbory\Base\Jobs\UpdateRedirectUrlStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Output\OutputInterface;

class RedirectHealthCommand extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arbory.redirect-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an URL healthcheck to verify the redirects table `to_url` is available and update `status` field in table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ids = $this->getIDs();
        $job = new UpdateRedirectUrlStatus($ids);

        $this->info('Start to check '.count($ids).' entries...');
        try {
            $this->dispatchNow($job);
            $result = $job->getResult();
        } catch (\Exception $e) {
            $this->error('Command redirect-health failed with an exception');
            $this->error($e->getMessage());

            return 1;
        }

        if (! empty($result) && count($result->getInvalidUrlList())) {
            $this->warn(PHP_EOL . 'Invalid URLs list:');
            foreach ($result->getInvalidUrlList() as $url) {
                $this->warn($url);
            }
        }

        if ($this->isSetVerboseFlag() && ! empty($result) && count($result->getErrors())) {
            foreach ($result->getErrors() as $url => $err) {
                $this->error('Request to ' . $url . ' - ' . $err);
            }
        }

        $this->warn(PHP_EOL . 'Invalid entries: ' . $result->getInvalidCount());
        $this->info('Valid entries: ' . $result->getValidCount());

        return 0;
    }

    /**
     * @return bool
     */
    private function isSetVerboseFlag()
    {
        return $this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;
    }

    /**
     * @return array
     */
    public function selectAllRedirectIds()
    {
        $results = DB::table('redirects')->where('id', '>', 0)->pluck('id')->toArray();

        return $results;
    }

    /**
     * @param array $entryIds
     * @param int $status
     */
    public function setStatus(array $entryIds, int $status)
    {
        DB::table('redirects')->whereIn('id', $entryIds)->update(['status' => $status]);
    }

    /**
     * @return array
     */
    private function getIDs()
    {
        return $this->selectAllRedirectIds();
    }
}
