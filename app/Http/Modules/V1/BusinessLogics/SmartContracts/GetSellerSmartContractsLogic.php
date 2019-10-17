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
            'INPUT::UserDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $userDTO = $this->getScope('INPUT::UserDTO');

        $smartContracts = $this->smartContractRepository
            ->getSellerSmartContracts($userDTO->getId());

        $buyerUserIds = array_column($smartContracts->getCollection()->toArray(), 'buyer_user_id');

        $buyers = $this->smartContractApiRepository
            ->getUsersByIds($authorizationDTO->getBearerToken(), $buyerUserIds);

        $smartContracts->getCollection()->transform(function ($smartContract) use ($buyers){
            $smartContract->buyer_name = $buyers[$smartContract->buyer_user_id];
            return $smartContract;
        });

        $this->putScope('DB::SmartContracts', $smartContracts);
        return $smartContracts;
    }
}