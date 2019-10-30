<?php


namespace App\Http\Modules\V1\Repositories\API\Orders;


use GuzzleHttp\Client;

class OrderApiRepository
{
    public function generateOrderSerialsBasedOnDates($payloads)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/orders/serial-numbers', [
            'headers' => [
                'Authorization' => $payloads['authorization']
            ],
            'query' => [
                'order_dates' => $payloads['order_dates']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function getOrderForSmartContractLegalContent($payloads)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/orders/smart-contract-legal', [
            'headers' => [
                'Authorization' => $payloads['authorization']
            ],
            'query' => [
                'order_serial' => $payloads['order_serial']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}