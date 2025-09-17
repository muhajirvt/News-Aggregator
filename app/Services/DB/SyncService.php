<?php

namespace App\Services\DB;
use DB;
use Log;

class SyncService
{
    public function __construct(
        protected ArticleStoreServices $articleStoreService,
        protected AuthorService $authorService,
        protected CategoryService $categoryService,
    ) {}

    public function sync($rawArticles): void
    {
        retry(2, function () use ($rawArticles) {
            DB::transaction(function () use ($rawArticles) {
                $allAuthors = $rawArticles->pluck('author')->flatten()->unique();
                $authors = $this->authorService->upsertAuthors($allAuthors);
                $allCategories = $rawArticles->pluck('category')->flatten()->unique();
                $categories = $this->categoryService->upsertCategories($allCategories);

                $article = [];
                foreach ($rawArticles as $item) {
                    $article[] = [
                        'source_id'    => $item["source_id"],
                        'author_id'    => !empty($authors[$item["author"]]) ? $authors[$item["author"]] : $authors["unknown"],
                        'category_id'  => !empty($categories[$item["category"]]) ? $categories[$item["category"]] : $categories["unknown"],
                        'title'        => $item['title'],
                        'description'  => $item['description'],
                        'published_at' => $item['published_at']
                    ];
                }
                $this->articleStoreService->insertArticles($article);
            });
        }, 1000, function ($e) {
            Log::error("Article insertion error");
            report($e);
        });
    }
}
