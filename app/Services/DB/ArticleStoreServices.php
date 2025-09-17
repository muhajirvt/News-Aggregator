<?php

namespace App\Services\DB;

use App\Models\Article;

class ArticleStoreServices
{
    public function insertArticles(array $articles): void
    {
        collect($articles)->chunk(2000)->each(function ($chunk) {
            Article::insert($chunk->toArray());
        });
    }
}
