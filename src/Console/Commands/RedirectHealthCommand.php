<?php

namespace Arbory\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Arbory\Base\Jobs\UpdateRedirectUrlStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RedirectHealthCommand extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arbory.redirect-health
                            {ids=[] : The array of IDs from redirects table to check (if not provided then would be selected all redirects table entries)}
                            {--errors : Show curl request errors}';

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
        try {
            $ids = $this->getIDs();
            $job = new UpdateRedirectUrlStatus($ids);

            $this->info('Start to check '.count($ids).' entries...');

            $this->dispatchNow($job);
            $result = $job->getResult();

            if (! empty($result) && count($result->getInvalidUrlList())) {
                $this->warn("\nInvalid URLs list:");
                foreach ($result->getInvalidUrlList() as $url) {
                    $this->warn($url);
                }
            }

            if ($this->option('errors') && ! empty($result) && count($result->getErrors())) {
                foreach ($result->getErrors() as $url => $err) {
                    $this->error("Request to $url - $err");
                }
            }

            $this->warn("\nInvalid entries: {$result->getInvalidCount()}");
            $this->info("Valid entries: {$result->getValidCount()}");
        } catch (\Exception $e) {
            $this->error('Redirects healthcheck failed with an exception');
            $this->error($e->getMessage());

            return 2;
        }

        return 0;
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
        $param = $this->argument('ids');

        $final_ids = [];
        foreach (explode(',', $param) as $id) {
            $final_ids[] = $id;
        }

        if (count($final_ids)) {
            return $final_ids;
        }

        return $this->selectAllRedirectIds();
    }

}