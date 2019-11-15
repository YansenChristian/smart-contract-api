<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\Emails\UserDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Legals\LegalRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class ApproveSmartContractRequestLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $legalRepository;
    private $orderApiRepository;
    private $userApiRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->legalRepository = new LegalRepository();
        $this->orderApiRepository = new OrderApiRepository();
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
            'INPUT::AuthorizationDTO' => 'required',
            'INPUT::SmartContractDTO' => 'required',
            'INPUT::SellerDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');
        $sellerDTO = $this->getScope('INPUT::SellerDTO');

        $this->smartContractRepository->updateStatusBySerialNumber($smartContractDTO->smart_contract_serial, $smartContractDTO->smart_contract_status);
        $this->legalRepository->signContractAsSeller($smartContractDTO->smart_contract_serial, $sellerDTO);
        $this->activateSmartContractOrder($smartContractDTO, $authorizationDTO);

        $smartContract = $this->smartContractRepository->getBySerialNumber(['buyer_user_id'], $smartContractDTO->smart_contract_serial);
        $smartContractDTO->buyer_user_id = $smartContract->buyer_user_id;
        $this->sendSmartContractApprovalEmailToBuyer($smartContractDTO, $authorizationDTO);
        $this->createLog($smartContractDTO, $sellerDTO);
    }

    private function activateSmartContractOrder($smartContractDTO, $authorizationDTO)
    {
        $orderSerial = $this->smartContractRepository->getSmartContractFirstOrderSerial($smartContractDTO->smart_contract_serial);

        $payloads = [
            'order_serial' => $orderSerial
        ];

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $this->orderApiRepository->activateSmartContractOrder($payloads, $headers);
    }

    private function sendSmartContractApprovalEmailToBuyer($smartContractDTO, $authorizationDTO)
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
            '-view_legal_link-' => env('SMART_CONTRACT_URL').'v1/legals?smart_contract_serial='.$smartContractDTO->smart_contract_serial
        ]);

        return sendgridMail(
            $sender->email,
            $sender->name,
            $receiver->email,
            $receiver->name,
            null,
            'd-9e191fb0317b4735ba3ba2191985dc15',
            $emailParameters,
            'Smart Contract Telah Disetujui'
        );
    }

    public function createLog($smartContractDTO, $sellerDTO)
    {
        $smartContractLogDTO = new SmartContractLogDTO();
        $smartContractLogDTO->user_id = $sellerDTO->id;
        $smartContractLogDTO->smart_contract_serial = $smartContractDTO->smart_contract_serial;
        $smartContractLogDTO->smart_contract_status = $smartContractDTO->smart_contract_status['name'];
        $smartContractLogDTO->information = trans('SmartContracts/status.approved');

        $this->logRepository->addSmartContractLog($smartContractLogDTO->toArray());
    }
}