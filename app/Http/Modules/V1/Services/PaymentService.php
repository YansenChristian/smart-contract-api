<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Payments\GetPaymentDataLogic;
use App\Http\Modules\V1\Service;
use GuzzleHttp\Client;

class PaymentService extends Service
{
    public function getById($paymentId)
    {
        $scopes = [
            'INPUT::PaymentId' => $paymentId
        ];

        $response = $this->execute([
            GetPaymentDataLogic::class
        ], $scopes);

        return $response->get(GetPaymentDataLogic::class);
    }

    public function confirmOrderPayment(array $headers, array $payloads)
    {
        $requestBody = [
            'order_serial' => $payloads['order_serial'],
            'amount' => $payloads['transfer_nominal']
        ];

        try{
            $apiCall = new Client();
            $response = $apiCall->post(
                env('API_CORE_URL').'api/v2/payment-buyer/handle-transfer',
                [
                    'headers' => $headers,
                    'body' => json_encode($requestBody)
                ]
            );
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }
}