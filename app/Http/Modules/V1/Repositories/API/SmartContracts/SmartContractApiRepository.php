<?php


namespace App\Http\Modules\V1\Repositories\API\SmartContracts;


use GuzzleHttp\Client;

class SmartContractApiRepository
{
    public function getUsersByIds($authorizationToken, $userIds)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . '/api/v4/users', [
            'headers' => [
                'Authorization' => $authorizationToken
            ],
            'query' => [
                'user_ids' => $userIds
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}