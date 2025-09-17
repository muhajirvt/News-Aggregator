<?php

namespace App\Services\API;

use App\Interfaces\NewsInterface;
use Log;
use Http;
use Carbon\Carbon;
use Exception;
use App\Helpers\CommonHelper;


class NYTService implements NewsInterface
{
    private int $pageNumber = 1;
    private int $pageSize = 10;
    private string $startDateTime;

    public function fetch($source)
    {
        $this->startDateTime = Carbon::yesterday()->startOfDay()->format("Ymd");

        $response = $this->callApi($source);
        if (empty($response['response']['docs']))
        {
            Log::info("NYT: No articles found");
            return [];
        }
        $results = $response["response"]['docs'];

        $totalResults = (int)$response["response"]["metadata"]["hits"] - $this->pageSize; // since the 1st page records are already fetched
        if ($totalResults > 0)
        {
            $totalPages = CommonHelper::customCeilDivision($totalResults, $this->pageSize) + 1;
            for ($this->pageNumber=2; $this->pageNumber <= $totalPages; $this->pageNumber++)
            {
                $res = $this->callApi($source);
                if (!empty($res['response']['docs']))
                {
                    $results = array_merge($results, $res['response']['docs']);
                }
            }
        }

        $articles = [];
        foreach ($results as $item)
        {
            $articles[] = [
                "source_id" => $source->id,
                "title" => $item["headline"]["main"],
                "description" => $item["snippet"],
                "category" => strtolower(trim($item["section_name"] ?? "Unknown")),
                "published_at" => Carbon::parse($item["pub_date"])->format("Y-m-d H:i:s"),
                "author" => strtolower(trim($item["author"] ?? "Unknown")),
            ];
        }
        return $articles;
    }

    private function callApi($source)
    {
        $params = [
            'q' => 'software',
            'api-key' => $source->api_key,
            'begin_date' => $this->startDateTime,
            'page' => $this->pageNumber,
        ];
        try
        {
            $url = "{$source->url}/svc/search/v2/articlesearch.json?" . http_build_query($params);

             $response = Http::retry(3, 1000, function($exception){
                Log::warning("NYT: Retrying request for page number : $this->pageNumber...", [
                    "error" => $exception?->getMessage(),
                ]);
            })->get($url);
            if ($response->failed())
            {
                $data = $response->json();

                $errorMessage = (($data['fault']["faultstring"]) ? $data['fault']["faultstring"] : 'Unknown error') . " while fetching data in page number: $this->pageNumber";
                Log::error("NYT: $errorMessage");
                return;
            }
            return $response->json();
        }
        catch (Exception $e)
        {
            report($e);
            Log::error("NYT: Fetching error");
        }
    }
}
