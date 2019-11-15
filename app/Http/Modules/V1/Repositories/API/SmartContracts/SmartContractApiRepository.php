<?php


namespace App\Http\Modules\V1\Repositories\API\SmartContracts;


use App\Http\Modules\V1\DataTransferObjects\Emails\UserDTO;
use GuzzleHttp\Client;

class SmartContractApiRepository
{
    public function getSmartContractDetail($payloads)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/smart-contracts/details', [
            'headers' => [
                'Authorization' => $payloads['authorization']
            ],
            'query' => [
                'order_serials' => $payloads['order_serials']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}