<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Source;
use App\Factories\ArticleServicesFactory;
use App\Services\DB\SyncService;
use App\Services\DB\{ArticleStoreServices,AuthorService,CategoryService};
use Log;

class FetchArticlesJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $sources = Source::getActiveSources(['id', 'name', 'identifier', 'url', 'api_key']);
        $articles = [];
        foreach($sources as $source){
            $articleFactory = ArticleServicesFactory::create($source->identifier);
            Log::info("$source->identifier: fetching started");

            $result = $articleFactory->fetch($source);

            Log::info("$source->identifier: fetching completed");

            if (!empty($result)) {
                $articles = array_merge($articles, $result);
            }
        }
        $syncServcie = new SyncService(new ArticleStoreServices(), new AuthorService(), new CategoryService());
        $syncServcie->sync(collect($articles));
        Log::info("Article sync completed");
    }
}
