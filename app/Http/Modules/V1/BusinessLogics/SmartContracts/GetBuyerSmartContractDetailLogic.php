<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\SmartContracts\SmartContractApiRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetBuyerSmartContractDetailLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $smartContractApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->smartContractApiRepository = new SmartContractApiRepository();
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

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');

        $smartContract = $this->smartContractRepository->getBySerialNumber([
            'smart_contracts.id',
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.total_price',
            'smart_contracts.total_order',
            'smart_contracts.on_going_order',
            'smart_contract_status.name AS status',
        ], $smartContractDTO->smart_contract_serial);

        $smartContractDetail = $this->smartContractRepository->getDetail($smartContract->id);
        $orderSerials = array_column($smartContractDetail->toArray(), 'order_serial');

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $payloads = [
            'order_serials' => $orderSerials,
            'role' => 'Buyer'
        ];

        $orderDetail = $this->smartContractApiRepository
            ->getBuyerSmartContractDetail($payloads, $headers);

        return array_merge((array)$smartContract, $orderDetail);
    }
}