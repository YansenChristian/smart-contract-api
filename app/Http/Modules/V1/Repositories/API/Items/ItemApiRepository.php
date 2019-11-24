<?php


namespace App\Http\Modules\V1\Repositories\API\Items;


use GuzzleHttp\Client;

class ItemApiRepository
{
    public function getItemsByIds($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/items', [
            'headers' => $headers,
            'query' => $payloads
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}