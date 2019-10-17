<?php


namespace App\Http\Modules\V1\BusinessLogics\Payments;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Payments\PaymentRepository;

class GetPaymentDataLogic extends BusinessLogic
{
    private $paymentRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->paymentRepository = new PaymentRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::PaymentId' => 'required|payment_exists'
        ]);

        $paymentId = $this->getScope('INPUT::PaymentId');
        return $this->paymentRepository
            ->getById($paymentId);
    }
}