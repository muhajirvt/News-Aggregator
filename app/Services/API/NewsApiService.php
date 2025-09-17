<?php

namespace App\Services\API;

use App\Interfaces\NewsInterface;
use Log;
use Http;
use Carbon\Carbon;
use Exception;
use App\Helpers\CommonHelper;

class NewsApiService implements NewsInterface
{
    private int $pageNumber = 1;
    private int $pageSize   = 100;
    private string $startDateTime;
    private string $endDateTime;

    public function fetch($source)
    {
        $this->startDateTime = Carbon::now()->subMinutes(15)->toIso8601String();
        $this->endDateTime   = Carbon::now()->toIso8601String();

        $response = $this->callApi($source);

        if (empty($response['articles']))
        {
            Log::info("NewsAPI: No articles found");
            return [];
        }
        $results = $response['articles'];

        $totalResults = (int)$response["totalResults"] - $this->pageSize; // since the 1st page records are already fetched

        if ($totalResults > 0)
        {
            $totalPages = CommonHelper::customCeilDivision($totalResults, $this->pageSize) + 1;
            for ($this->pageNumber=2; $this->pageNumber <= $totalPages; $this->pageNumber++)
            {
                $res = $this->callApi($source);
                if (!empty($res["articles"]))
                {
                    $results = array_merge($results, $res["articles"]);
                }
            }
        }
        $articles = [];
        foreach ($results as $item)
        {
            $articles[] = [
                "source_id"    => $source->id,
                "title"        => $item["title"],
                "description"  => $item["description"],
                "category"     => strtolower(trim($item["category"] ?? "Unknown")),
                "published_at" => Carbon::parse($item["publishedAt"])->format("Y-m-d H:i:s"),
                "author"       => strtolower(trim($item["author"] ?? "Unknown")),
            ];
        }
        return $articles;
    }

    private function callApi($source)
    {
        try{
            $params = [
                'q'        => 'software',
                'apiKey'   => $source->api_key,
                'from'     => $this->startDateTime,
                'to'       => $this->endDateTime,
                'page'     => $this->pageNumber,
                'pageSize' => $this->pageSize,
            ];
            $url = "$source->url/v2/everything?" . http_build_query($params);
            $response = Http::retry(3, 1000, function($exception){
                // 3 → Number of retries (so it will retry once if the first attempt fails).
                // 2000 → Delay between retries (in milliseconds) = 2 seconds.
                // function($exception) → Callback executed when a retry happens, usually for logging.
                Log::warning("NewsAPI: Retrying request for page number : $this->pageNumber...", [
                    "error" => $exception?->getMessage(),
                ]);
            })->get($url);

            if ($response->failed())
            {
                $data = $response->json();
                $errorMessage = ($data['message'] ?? $data['message'] ?? 'Unknown error') . " while fetching data in page number: $this->pageNumber";
                Log::error("NewsAPI: $errorMessage");
                return;
            }
            return $response->json();
        }catch (Exception $e){
            report($e);
            Log::error("NewsAPI: Fetching error");
        }
    }
}
