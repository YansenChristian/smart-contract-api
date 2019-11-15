<?php


namespace App\Http\Modules\V1\Repositories\API\Users;


use GuzzleHttp\Client;

class UserApiRepository
{
    public function getUsersByIds($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'api/v4/users', [
            'headers' => $headers,
            'query' => [
                'user_ids' => $payloads['user_ids']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

}