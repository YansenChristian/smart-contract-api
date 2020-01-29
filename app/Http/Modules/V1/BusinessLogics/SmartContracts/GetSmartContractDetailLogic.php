<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\SmartContracts\SmartContractApiRepository;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSmartContractDetailLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $smartContractApiRepository;
    private $userApiRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->userApiRepository = new UserApiRepository();
        $this->smartContractApiRepository = new SmartContractApiRepository();
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
            'INPUT::AuthorizationDTO' => 'required',
        ]);

        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        $smartContract = $this->smartContractRepository->getSmartContract($smartContractDTO->smart_contract_serial);
        $orderSerials = $this->smartContractRepository->getDetail($smartContract->id)->pluck('order_serial')->toArray();
        $logs = $this->getSmartContractLogs($smartContractDTO->smart_contract_serial);
        $smartContractDetail = $this->getSmartContractDetail($smartContract, $orderSerials, $authorizationDTO);

        return [
            'smart_contract' => $smartContract,
            'smart_contract_detail' => $smartContractDetail,
            'logs' => $logs
        ];
    }

    private function getSmartContractDetail($smartContract, $orderSerials, $authorizationDTO)
    {
        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $payloads = [
            'buyer_user_id' => $smartContract->buyer_user_id,
            'vendor_id' => $smartContract->vendor_id,
            'payment_method_id' => $smartContract->payment_method_id,
            'item_id' => $smartContract->item_id,
            'order_serials' => $orderSerials,
        ];

        return $this->smartContractApiRepository->getSmartContractDetail($payloads, $headers);
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
            $log->user_role = $users[encode($log->user_id)]['type'];
        }

        return $smartContractLogs;
    }
}