<?php


namespace App\Http\Modules\V1\Repositories\API\SmartContracts;


use App\Http\Modules\V1\DataTransferObjects\Emails\UserDTO;
use GuzzleHttp\Client;

class SmartContractApiRepository
{
    public function getSellerSmartContractDetail($payloads)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/smart-contracts/details', [
            'headers' => [
                'Authorization' => $payloads['authorization']
            ],
            'query' => [
                'role' => $payloads['role'],
                'order_serials' => $payloads['order_serials']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function getBuyerSmartContractDetail($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/smart-contracts/details', [
            'headers' => $headers,
            'query' => $payloads,
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function getSmartContracts($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->post(env('API_CORE_URL') . 'v4/smart-contracts/get', [
            'headers' => $headers,
            'form_params' => $payloads,
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function getSmartContractDetail($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/smart-contracts/detail', [
            'headers' => $headers,
            'query' => $payloads,
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}