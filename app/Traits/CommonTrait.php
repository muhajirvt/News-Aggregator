<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

trait CommonTrait
{
    public function callAPI(array $params = []): array
    {
        try {
            $sourceId = $params['source_id'];
            $response = Http::get($params['url'], $params['query']);
            $responseBody =  json_decode($response->body(), true);
            $responseData = [];
            $data = [];
            $success = false;
            if($response->status() == 200) {
                if($sourceId == 1) {
                    $data =  $responseBody['articles'];
                } else if ($sourceId == 2){
                    $data = $responseBody['response']['docs'];
                } else if($sourceId == 3){
                    $data = $responseBody['response']['results'];
                }
                $success = true;
                $responseData['success'] = $success;
                $responseData['data']    = $data;
            } if(in_array($response->status(),[400,401])) {
                $responseData['success'] = $success;
                $responseData['message'] = $responseBody['message'] ?? $responseBody['fault']['faultstring'];
                Log::info('Response', ['responseData' => $responseData]);
            }
            return $responseData;
        } catch (Exception $e) {
            Log::info('Exception', ['Error' => $e->getMessage()]);
            return [
                'success' => $success,
                'message' => $e->getMessage()
            ];
        }
    }
}
