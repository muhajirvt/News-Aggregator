<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Services\DB\ArticleFetchService;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function __construct(private ArticleFetchService $articleFetchService){}

    public function index(ArticleRequest $request)
    {
        $articles = $this->articleFetchService->getFilteredArticles($request->validated());
        $resource = ArticleResource::collection($articles);
        $data     = $this->articleFetchService->arrangementData($resource);
        return response()->json(['success' => true, 'data' => $data], 200);
        // $params = $request->validated();

        // $sourceId = $params['source_id'];
        // if($sourceId == 1){
        //     $params['url'] = "https://newsapi.org/v2/everything";
        //     $params['query'] = [
        //         'q'      => "Apple",
        //         'from'   => "2025-09-01",
        //         'sortBy' => "popularity",
        //         'apiKey' => "a0e64297d019409492ab1764a9fa7d24"
        //     ];
        //     return $this->callAPI($params);
        // } else if($sourceId == 2){
        //     $params['url'] = "https://api.nytimes.com/svc/search/v2/articlesearch.json";
        //     $params['query'] = [
        //         'q'       => "election",
        //         'api-key' => "3Kzl3USu5lXSZ7fzqK9dOo8pJcI92xdc"
        //     ];
        //     return $this->callAPI($params);
        // } else if($sourceId == 3){
        //     $params['url'] = "https://content.guardianapis.com/search";
        //     $params['query'] = [
        //         'q'         => "debate",
        //         'tag'       => "politics/politics",
        //         'from-date' => "2014-01-01",
        //         'api-key'   => "test"
        //     ];
        //     return $this->callAPI($params);
        // }
    }

    public function show(string $id)
    {
        $article = $this->articleFetchService->getByReference($id);
        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => "No Article found"
            ], 400);
        }
        return response()->json(['success' => true, 'data' => new ArticleResource($article)], 200);
    }
}
