<?php


namespace App\Http\Controllers\V1;


use App\Exceptions\ApiValidationException;
use App\Http\Modules\V1\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController
{
    public function getConfirmOrderPaymentViaEmail(Request $request, PaymentService $paymentService)
    {
        $rules = [
            'order_serial' => 'required|string',
            'transfer_nominal' => 'required|int'
        ];

        $headers = [
            'authorization' => $request->header('Authorization'),
            'content-type' => 'application/json'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $response = $paymentService->confirmOrderPayment($headers, $request->all());
        return response()->json([
            'message' => 'successful!',
        ], 200);
    }
}