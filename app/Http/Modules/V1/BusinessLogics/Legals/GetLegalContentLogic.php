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
            'order_serial' => $orderSerials[0]
        ];

        $headers = [
            'x-access-token' => $authorizationDTO->x_access_token
        ];

        $order = $this->orderApiRepository->getOrderForSmartContractLegalContent($payloads, $headers);

        $legalContent = (object) $order;
        $legalContent->order_serials = $orderSerials;
        $legalContent->order_date = $smartContractDetails[0]->created_at;

        $this->putScope('DB::LegalContent', $legalContent);
        return $legalContent;
    }
}