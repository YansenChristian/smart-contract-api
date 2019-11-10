<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSmartContractCounterLogic extends BusinessLogic
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
            'INPUT::VendorDTO' => 'required'
        ]);

        $vendorDTO = $this->getScope('INPUT::VendorDTO');

        $counterPerStatusName = $this->smartContractRepository
            ->getCounter($vendorDTO->id)
            ->pluck('subtotal', 'name');

        #calculate total count from each status
        $totalCounters = 0;
        foreach ($counterPerStatusName as $statusName => $subtotal) {
            $totalCounters += $subtotal;
        }
        $counterPerStatusName->put('ALL', $totalCounters);

        $this->putScope('DB::SmartContractCounters', $counterPerStatusName);
        return $counterPerStatusName;
    }
}