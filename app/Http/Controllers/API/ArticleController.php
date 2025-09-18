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
        return $this->articleFetchService->returnResponse(200, true, $data, []);
    }

    public function show(string $id)
    {
        $article = $this->articleFetchService->getById($id);
        if (!$article) {
            return $this->articleFetchService->returnResponse(400, false, [], "No Article found");
        }
        $data = new ArticleResource($article);
        return $this->articleFetchService->returnResponse(200, true, $data, []);
    }
}
