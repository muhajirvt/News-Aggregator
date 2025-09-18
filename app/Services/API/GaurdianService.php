<?php

namespace App\Services\API;

use App\Interfaces\NewsInterface;
use Log;
use Http;
use Carbon\Carbon;
use Exception;
use App\Helpers\CommonHelper;

class GaurdianService implements NewsInterface
{
    private int $pageNumber = 1;
    private int $pageSize = 200;
    private string $startDateTime;


    public function fetch($source)
    {
        $this->startDateTime = Carbon::yesterday()->startOfDay()->format('Y-m-d\TH:i:s');

        $response = $this->callApi($source);

        if (empty($response["results"]))
        {
            Log::info("Gaurdian: No articles found");
            return [];
        }
        $results = $response["results"];
        $totalResults = (int)$response["total"] - $this->pageSize; // since the 1st page records are already fetched
        if ($totalResults > 0)
        {
            $totalPages = CommonHelper::customCeilDivision($totalResults, $this->pageSize) + 1;
            for ($this->pageNumber=2; $this->pageNumber <= $totalPages; $this->pageNumber++)
            {
                $res = $this->callApi($source, $response);
                if (!empty($res["results"]))
                {
                    $results = array_merge($results, $res["results"]);
                }
            }
        }
        $articles = [];
        foreach ($results as $item)
        {
            $articles[] = [
                "source_id"    => $source->id,
                "title"        => $item["webTitle"],
                "description"  => "",
                "category"     => strtolower(trim($item["sectionName"] ?? "Unknown")),
                "published_at" => Carbon::parse($item["webPublicationDate"])->format("Y-m-d H:i:s"),
                "author"       => strtolower(trim($item["author"] ?? "Unknown")),
            ];
        }
        return $articles;
    }

    private function callApi($source)
    {
        $params = [
            'q'         => 'software',
            'api-key'   => $source->api_key,
            'from-date' => $this->startDateTime,
            'page'      => $this->pageNumber,
            'page-size' => $this->pageSize,
        ];

        try
        {
            $url = "$source->url/search?" . http_build_query($params);
            $response = Http::retry(3, 1000, function($exception){
                Log::warning("Gaurdian: Retrying request for page number : $this->pageNumber...", [
                    "error" => $exception?->getMessage(),
                ]);
            })->get($url);

            if ($response->failed())
            {
                $data = $response->json();
                $errorMessage = ($data['response']['message'] ?? $data['response']['message'] ?? 'Unknown error') . " while fetching data in page number: $this->pageNumber";
                Log::error("Gaurdian: $errorMessage");
                return;
            }

            return $response->json()["response"];
        }
        catch (Exception $e)
        {
            report($e);
            Log::error("Gaurdian: Fetching error");
        }
    }
}
