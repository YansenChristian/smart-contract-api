<?php


namespace App\Http\Modules\V1\BusinessLogics\Orders;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;

class CreateSmartContractOrdersLogic extends BusinessLogic
{
    private $orderApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->orderApiRepository = new OrderApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::AuthorizationDTO' => 'required',
            'API::OrderSerials' => 'required',
            'INPUT::CheckoutData' => 'required',
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $orderSerials = $this->getScope('API::OrderSerials');
        $checkoutData = $this->getScope('INPUT::CheckoutData');

        $checkoutData['order_serials'] = $orderSerials;
        $checkoutData['user_id'] = decode($checkoutData['user_id']);
        $checkoutData['cart_id'] = decode($checkoutData['cart_id']);
        $checkoutData['shipping_address_id'] = decode($checkoutData['shipping_address_id']);
        $checkoutData['billing_address_id'] = decode($checkoutData['billing_address_id']);
        $checkoutData['payment_id'] = decode($checkoutData['payment_id']);

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $this->orderApiRepository->createSmartContractOrders($checkoutData, $headers);
    }
}