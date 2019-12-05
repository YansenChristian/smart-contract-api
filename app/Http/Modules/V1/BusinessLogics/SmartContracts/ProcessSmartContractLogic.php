<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\Emails\UserDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class ProcessSmartContractLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $userApiRepository;
    private $orderApiRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->userApiRepository = new UserApiRepository();
        $this->orderApiRepository = new OrderApiRepository();
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

        $smartContract = $this->smartContractRepository->getBySerialNumber(['*'], $smartContractDTO->smart_contract_serial);
        $smartContractDTO->buyer_user_id = $smartContract->buyer_user_id;
        $smartContractDTO->smart_contract_status_id = $smartContractDTO->smart_contract_status['id'];
        $smartContractDTO->on_going_order = $smartContract->on_going_order + 1;

        $this->smartContractRepository->update(
            $smartContractDTO->smart_contract_serial,
            $smartContractDTO->toArray()
        );

        $this->sendNextProgressSmartContractEmailToBuyer($smartContractDTO, $authorizationDTO);
        $this->createLog($smartContractDTO);
    }

    private function sendNextProgressSmartContractEmailToBuyer($smartContractDTO, $authorizationDTO)
    {
        $payloads = [
            'user_ids' => [encode($smartContractDTO->buyer_user_id)]
        ];

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }
        $user = $this->userApiRepository->getUsersByIds($payloads, $headers);
        $user = $user[encode($smartContractDTO->buyer_user_id)];

        $sender = new UserDTO();
        $sender->name = env('COMPANY_DOMAIN');
        $sender->email = env('EMAIL_NO_REPLY');

        $receiver = new UserDTO();
        $receiver->name = $user['name'];
        $receiver->email = $user['email'];

        $emailParameters = json_encode([
            '-buyer_name-' => $user['name'],
        ]);

        return sendgridMail(
            $sender->email,
            $sender->name,
            $receiver->email,
            $receiver->name,
            null,
            'd-9e191fb0317b4735ba3ba2191985dc15',
            $emailParameters,
            'Progress Smart Contract Berikutnya'
        );
    }

    public function createLog($smartContractDTO)
    {
        $smartContractLogDTO = new SmartContractLogDTO();
        $smartContractLogDTO->user_id = 0;
        $smartContractLogDTO->smart_contract_serial = $smartContractDTO->smart_contract_serial;
        $smartContractLogDTO->smart_contract_status = SmartContractStatus::IN_PROGRESS['name'];
        $smartContractLogDTO->information = 'SmartContracts/status_detail.in_progress_'.$smartContractDTO->on_going_order;

        $this->logRepository->addSmartContractLog($smartContractLogDTO->toArray());
    }
}