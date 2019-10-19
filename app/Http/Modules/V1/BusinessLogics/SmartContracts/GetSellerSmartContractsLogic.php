<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\SmartContracts\SmartContractApiRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSellerSmartContractsLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $smartContractApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->smartContractApiRepository = new SmartContractApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::AuthorizationDTO' => 'required',
            'INPUT::Filters' => 'required',
            'INPUT::PerPage' => 'required',
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $filters = $this->getScope('INPUT::Filters');
        $perPage = $this->getScope('INPUT::PerPage');

        $smartContracts = $this->smartContractRepository
            ->getSellerSmartContracts($filters, $perPage);

        $buyerUserIds = array_column($smartContracts->getCollection()->toArray(), 'buyer_user_id');

        if(count($buyerUserIds) > 0) {
            $buyerUserIds = array_map("encode", $buyerUserIds);
            $buyers = $this->smartContractApiRepository
                ->getUsersByIds($authorizationDTO->getBearerToken(), $buyerUserIds);

            $smartContracts->getCollection()->transform(function ($smartContract) use ($buyers){
                $smartContract->buyer_name = $buyers[$smartContract->buyer_user_id];
                return $smartContract;
            });
        }

        $this->putScope('DB::SmartContracts', $smartContracts);
        return $smartContracts;
    }
}