<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSmartContractLegalContentLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $orderApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->orderApiRepository = new OrderApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::SmartContractSerial' => 'required',
            'INPUT::AuthorizationDTO' => 'required'
        ]);

        $smartContractSerial = $this->getScope('INPUT::SmartContractSerial');
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        $legalContent = $this->smartContractRepository->getLegalContent($smartContractSerial);

        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'order_serial' => $legalContent->pluck('order_serial')[0]
        ];
        $order = $this->orderApiRepository->getOrderForSmartContractLegalContent($payloads);

        //TODO merge $legalContent and $order, and then return it
    }
}