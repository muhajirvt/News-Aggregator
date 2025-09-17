<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchArticlesJob;

class FetchArticlesCommand extends Command
{
    protected $signature = 'news:fetch-all';

    protected $description = 'Fetch news from the external api and store in to local database for listing purpose';

    public function handle()
    {
        FetchArticlesJob::dispatchSync();
    }
}
