<?php

namespace App\Services\DB;

use App\Models\Article;

class ArticleFetchService
{
    const MAX_LIMIT = 200;

    public function getFilteredArticles(array $params)
    {
        $query = Article::withDetails();
        // Filter by source
        $query->when(!empty($params['source_id']), function ($q) use ($params) {
            $q->where('articles.source_id', $params['source_id']);
        });

        // Filter by category
        $query->when(!empty($params['category']), function ($q) use ($params) {
            $q->where('categories.name', $params['category']);
        });

        // Filter by author
        $query->when(!empty($params['author']), function ($q) use ($params) {
            $q->where('authors.name', $params['author']);
        });

        // Search in title and description
        $query->when(!empty($params['search']), function ($q) use ($params) {
            $q->where(function ($subQuery) use ($params) {
                $subQuery->where('articles.title', 'like', '%' . $params['search'] . '%')
                ->orWhere('articles.description', 'like', '%' . $params['search'] . '%');
            });
        });

        // Filter by published_at >= date_from
        $query->when(!empty($params['date_from']), function ($q) use ($params) {
            $q->where('articles.published_at', '>=', $params['date_from']);
        });

        // Filter by published_at <= date_to
        $query->when(!empty($params['date_to']), function ($q) use ($params) {
            $q->where('articles.published_at', '<=', $params['date_to']);
        });

        return $query->orderBy('articles.id')->paginate($params['per_page'] ?? self::MAX_LIMIT);
    }

    public function getById($id)
    {
        return Article::withDetails()->where('articles.id', $id)->first();
    }

    public function arrangementData($articles) :array
    {
        return [
            "total"        => $articles->total(),
            "per_page"     => $articles->perPage(),
            "current_page" => $articles->currentPage(),
            "last_page"    => $articles->lastPage(),
            "articles"     => $articles
        ];
    }

    public function returnResponse($code, $success, $data, $message){
        $array            = [];
        $array['success'] = $success;
        if(!empty($data)){
            $array['data'] = $data;
        }

        if(!empty($message)){
            $array['message'] = $message;
        }

        return response()->json($array, $code);
    }
}
