<?php


namespace App\Http\Modules\V1\Repositories\API\Orders;


use GuzzleHttp\Client;

class OrderApiRepository
{
    public function generateOrderSerialsBasedOnDates($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/orders/serial-numbers', [
            'headers' => $headers,
            'query' => $payloads
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function getOrderForSmartContractLegalContent($payloads, $headers)
    {
        $client = new Client();
        $request_api = $client->get(env('API_CORE_URL') . 'v4/orders/smart-contract-legal', [
            'headers' => $headers,
            'query' => [
                'order_serial' => $payloads['order_serial']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function createSmartContractOrders($payloads, $header)
    {
        $client = new Client();
        $request_api = $client->post(env('API_CORE_URL') . 'api/v2/order/transaction', [
            'auth' => [
                getenv('API_USERNAME'),
                getenv('API_PASSWORD'),
            ],
            'headers' => $header,
            'form_params' => $payloads
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function activateOrder($payloads, $header)
    {
        $client = new Client();
        $request_api = $client->post(env('API_CORE_URL') . 'v4/orders/activate', [
            'headers' => $header,
            'form_params' => $payloads
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function acceptOrder($payloads)
    {
        $client = new Client();
        $request_api = $client->post(env('API_SELLER_URL') . 'v2/orders/accept-all-order/'.orderSerialToAlias($payloads['order_serial']), [
            'headers' => [
                'Authorization' => 'Bearer '.env('BEARER_TOKEN')
            ],
            'query' => [
                'user_id' => $payloads['user_id'],
                'user_name' => $payloads['user_name']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }

    public function rejectOrder($payloads)
    {
        $client = new Client();
        $request_api = $client->post(env('API_SELLER_URL') . 'v2/orders/reject-all-order/'.orderSerialToAlias($payloads['order_serial']), [
            'headers' => [
                'Authorization' => 'Bearer '.env('BEARER_TOKEN')
            ],
            'query' => [
                'user_id' => $payloads['user_id'],
                'user_name' => $payloads['user_name']
            ]
        ]);

        return json_decode($request_api->getBody()->getContents(), true);
    }
}