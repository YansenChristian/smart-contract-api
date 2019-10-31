<?php


namespace App\Http\Modules\V1\BusinessLogics\Legals;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\Database\Legals\LegalRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetLegalContentLogic extends BusinessLogic
{
    private $legalRepository;
    private $orderApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->legalRepository = new LegalRepository();
        $this->orderApiRepository = new OrderApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::SmartContractDTO' => 'required',
            'INPUT::AuthorizationDTO' => 'required'
        ]);

        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        $smartContractDetails = $this->legalRepository
            ->getContent($smartContractDTO->smart_contract_serial)
            ->toArray();

        $orderSerials = array_column($smartContractDetails, 'order_serial');

        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'order_serial' => $orderSerials[0]
        ];
        $order = $this->orderApiRepository->getOrderForSmartContractLegalContent($payloads);

        $legalContent = (object) $order;
        $legalContent->order_serials = $orderSerials;

        $this->putScope('DB::LegalContent', $legalContent);
        return $legalContent;
    }
}