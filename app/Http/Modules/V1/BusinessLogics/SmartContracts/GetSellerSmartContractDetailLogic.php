<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\SmartContracts\SmartContractApiRepository;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSellerSmartContractDetailLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $smartContractApiRepository;
    private $userApiRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->smartContractApiRepository = new SmartContractApiRepository();
        $this->userApiRepository = new UserApiRepository();
        $this->logRepository = new LogRepository();
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
        $smartContract = $this->smartContractRepository->getSellerSmartContract(
            $smartContractDTO->smart_contract_serial
        );

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        # Get Smart Contract Detail from API Core
        $orderSerials = $this->smartContractRepository
            ->getDetail($smartContract->id)
            ->pluck('order_serial')
            ->toArray();
        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'order_serials' => $orderSerials
        ];
        $smartContractDetails = $this->smartContractApiRepository->getSmartContractDetail($payloads);

        # Get & Render Smart Contract Log
        $smartContractLogs = $this->logRepository
            ->getSmartContractLog($smartContract->id);
        $userIds = $smartContractLogs->pluck('user_id')->toArray();
        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'user_ids' => $userIds
        ];
        $users = $this->userApiRepository->getUsersByIds($payloads);

        foreach ($smartContractLogs as $log) {
            $log->user_name = $users[$log->user_id];
        }

        $smartContractDetail = (object) array_merge(
            (array)$smartContractDetails,
            (array)$smartContract
        );
        $smartContractDetail->logs = $smartContractLogs->toArray();

        $this->putScope('DB::SmartContractDetail', $smartContractDetail);
        return $smartContractDetail;
    }
}