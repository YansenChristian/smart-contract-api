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

        # Get Smart Contract Detail Data
        $smartContract = $this->smartContractRepository->getSellerSmartContract(
            $smartContractDTO->smart_contract_serial
        );
        $smartContractOrders = $this->getSmartContractOrders($smartContract->id);
        $smartContractLogs = $this->getSmartContractLogs($smartContract->smart_contract_serial);

        # Merging Data
        $smartContractDetail = (object) array_merge(
            (array)$smartContractOrders,
            (array)$smartContract
        );
        $smartContractDetail->logs = $smartContractLogs->toArray();

        $this->putScope('DB::SmartContractDetail', $smartContractDetail);
        return $smartContractDetail;
    }

    private function getSmartContractOrders($smartContractId)
    {
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $orderSerials = $this->smartContractRepository
            ->getDetail($smartContractId)
            ->pluck('order_serial')
            ->toArray();

        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'order_serials' => $orderSerials,
            'role' => 'Seller'
        ];

        return $this->smartContractApiRepository->getSellerSmartContractDetail($payloads);
    }

    private function getSmartContractLogs($smartContractSerial)
    {
        $smartContractLogs = $this->logRepository->getSmartContractLog($smartContractSerial);
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $userIds = $smartContractLogs->pluck('user_id')->toArray();
        $userIds = array_map('encode', $userIds);

        # Append User Data by using User Id
        $payloads = [
            'authorization' => $authorizationDTO->bearer,
            'user_ids' => $userIds,
        ];

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $users = $this->userApiRepository->getUsersByIds($payloads, $headers);

        foreach ($smartContractLogs as $log) {
            $log->user_name = $users[encode($log->user_id)]['name'];
        }

        return $smartContractLogs;
    }
}