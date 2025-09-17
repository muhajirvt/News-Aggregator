<?php
namespace App\Factories;

use App;
use App\Services\API\NewsApiService;
use App\Services\API\NYTService;
use App\Services\API\GaurdianService;

class ArticleServicesFactory
{
    public function __construct(){}

    public static function create(string $identifier)
    {
        return match($identifier)
        {
            "newsapi"  => App::make(NewsApiService::class),
            "nytimes"  => App::make(NYTService::class),
            "guardian" => App::make(GaurdianService::class),
            "default"  => Log::error("$identifier is not integrated yet")
        };
    }
}
