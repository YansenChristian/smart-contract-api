<?php


namespace App\Http\Modules\V1\BusinessLogics\Orders;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDetailDTO;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class CheckIfOrderIsSmartContractLogic extends BusinessLogic
{
    private $smartContractRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::SmartContractDetailDTO' => 'required'
        ]);

        $smartContractDetailDTO = $this->getScope('INPUT::SmartContractDetailDTO');

        $orderExists = $this->smartContractRepository->orderExists($smartContractDetailDTO->order_serial);

        return $orderExists;
    }
}