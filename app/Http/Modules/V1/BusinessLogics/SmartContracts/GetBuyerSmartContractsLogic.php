<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetBuyerSmartContractsLogic extends BusinessLogic
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
            'INPUT::Filters' => 'required',
            'INPUT::PerPage' => 'required',
        ]);

        $filters = $this->getScope('INPUT::Filters');
        $perPage = $this->getScope('INPUT::PerPage');

        $smartContracts = $this->smartContractRepository
            ->getBuyerSmartContracts($filters, $perPage);

        $smartContractSerials = $smartContracts->pluck('smart_contract_serial')->toArray();
        $smartContractsDetail = $this->smartContractRepository
            ->getSmartContractsDetailBySerialNumbers($smartContractSerials);

        $smartContracts->getCollection()->transform(function ($smartContract) use ($smartContractsDetail) {
            $smartContract->order_serial_of_next_order = $smartContractsDetail[$smartContract->id][$smartContract->on_going_order - 1]->order_serial;
            return $smartContract;
        });

        return $smartContracts;
    }
}