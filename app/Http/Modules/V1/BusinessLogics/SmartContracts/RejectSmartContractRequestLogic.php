<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\Emails\UserDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDetailDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class RejectSmartContractRequestLogic extends BusinessLogic
{
    private $userApiRepository;
    private $orderApiRepository;
    private $smartContractRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->userApiRepository = new UserApiRepository();
        $this->orderApiRepository = new OrderApiRepository();
        $this->smartContractRepository = new SmartContractRepository();
        $this->logRepository = new LogRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::AuthorizationDTO' => 'required',
            'INPUT::SmartContractDTO' => 'required',
            'INPUT::SellerDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');
        $sellerDTO = $this->getScope('INPUT::SellerDTO');

        $this->smartContractRepository->updateStatusBySerialNumber(
            $smartContractDTO->smart_contract_serial,
            $smartContractDTO->smart_contract_status
        );

        $smartContractDetailDTO = new SmartContractDetailDTO();
        $smartContractDetailDTO->order_serial = $this->smartContractRepository
            ->getSmartContractFirstOrderSerial($smartContractDTO->smart_contract_serial);
        $this->rejectOrder($smartContractDetailDTO, $sellerDTO);

        $smartContract = $this->smartContractRepository->getBySerialNumber(['buyer_user_id'], $smartContractDTO->smart_contract_serial);
        $smartContractDTO->buyer_user_id = $smartContract->buyer_user_id;
        $this->sendSmartContractRejectionEmailToBuyer($smartContractDTO, $authorizationDTO);
        $this->createSmartContractRejectedLog($smartContractDTO, $sellerDTO);
    }

    private function rejectOrder($smartContractDetailDTO, $sellerDTO)
    {
        $payloads = [
            'order_serial' => $smartContractDetailDTO->order_serial,
            'user_id' => $sellerDTO->id,
            'user_name' => ''
        ];

        $this->orderApiRepository->rejectOrder($payloads);
    }

    private function sendSmartContractRejectionEmailToBuyer($smartContractDTO, $authorizationDTO)
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
            'Smart Contract Telah Ditolak'
        );
    }

    public function createSmartContractRejectedLog($smartContractDTO, $sellerDTO)
    {
        $smartContractLogDTO = new SmartContractLogDTO();
        $smartContractLogDTO->user_id = $sellerDTO->id;
        $smartContractLogDTO->smart_contract_serial = $smartContractDTO->smart_contract_serial;
        $smartContractLogDTO->smart_contract_status = SmartContractStatus::REJECTED['name'];
        $smartContractLogDTO->information = 'SmartContracts/status_detail.rejected';

        $this->logRepository->addSmartContractLog($smartContractLogDTO->toArray());
    }
}