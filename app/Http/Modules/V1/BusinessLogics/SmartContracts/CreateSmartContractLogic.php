<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Repositories\API\Orders\OrderApiRepository;
use App\Http\Modules\V1\Repositories\Database\Legals\LegalRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class CreateSmartContractLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $orderApiRepository;
    private $legalRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->orderApiRepository = new OrderApiRepository();
        $this->legalRepository = new LegalRepository();
        $this->logRepository = new LogRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::OrderDates' => 'required',
            'INPUT::SmartContractDTO' => 'required',
            'INPUT::AuthorizationDTO' => 'required',
            'DB::SmartContractSerial' => 'required',
        ]);

        $smartContractSerial = $this->getScope('DB::SmartContractSerial');
        $smartContractDTO = $this->getScope('INPUT::SmartContractDTO');
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $orderDates = $this->getScope('INPUT::OrderDates');

        $smartContractDTO->smart_contract_serial = $smartContractSerial;
        $smartContractId = $this->smartContractRepository->create($smartContractDTO->toArray());

        $smartContractDTO->id = $smartContractId;

        $payloads = [
            'order_dates' => $orderDates
        ];
        $orderSerials = $this->generateOrderSerials($authorizationDTO, $payloads);
        $this->putScope('API::OrderSerials', $orderSerials);

        $this->createDetail($smartContractDTO, $orderSerials);
        $this->createLegal($smartContractDTO);
        $this->createLog($smartContractDTO);
    }

    private function generateOrderSerials($authorizationDTO, $payloads)
    {
        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        return $this->orderApiRepository->generateOrderSerialsBasedOnDates($payloads, $headers);
    }

    private function createDetail($smartContractDTO, $orderSerials)
    {
        $smartContractDetailData = [];
        foreach ($orderSerials as $orderSerial) {
            $smartContractDetailData[] = [
                'smart_contract_id' => $smartContractDTO->id,
                'order_serial' => $orderSerial
            ];
        }

        $this->smartContractRepository->createDetail($smartContractDetailData);
    }

    private function createLegal($smartContractDTO)
    {
        $legalData = [
            'smart_contract_serial' => $smartContractDTO->smart_contract_serial,
            'buyer_user_id' => $smartContractDTO->buyer_user_id,
            'buyer_approved_on' => date('d-m-Y')
        ];

        $this->legalRepository->create($legalData);
    }

    private function createLog($smartContractDTO)
    {
        $smartContractLogDTO = new SmartContractLogDTO();
        $smartContractLogDTO->smart_contract_serial = $smartContractDTO->smart_contract_serial;
        $smartContractLogDTO->smart_contract_status = SmartContractStatus::WAITING['name'];
        $smartContractLogDTO->information = trans(SmartContractStatus::WAITING['description']) ;
        $smartContractLogDTO->user_id = $smartContractDTO->buyer_user_id;

        $this->logRepository->addSmartContractLog($smartContractLogDTO->toArray());
    }
}