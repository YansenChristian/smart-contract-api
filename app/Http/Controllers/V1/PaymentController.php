<?php


namespace App\Http\Controllers\V1;


use App\Http\Modules\V1\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController
{
    public function getConfirmOrderPaymentViaEmail(Request $request, PaymentService $paymentService)
    {
        $rules = [
            'order_serial' => 'required',
            'transfer_nominal' => 'required|int'
        ];

        $payloads = [
            'order_serial' => $request->get('order_serial'),
            'transfer_nominal' => $request->get('transfer_nominal')
        ];

        $headers = [
            'authorization' => $request->header('Authorization'),
            'content-type' => 'application/json'
        ];

        $validator = Validator::make($payloads, $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $response = $paymentService->confirmOrderPayment($headers, $payloads);
        return response()->json([
            'message' => 'successful!',
        ], 200);
    }
}