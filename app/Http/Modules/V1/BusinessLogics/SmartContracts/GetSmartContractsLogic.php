<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\SmartContracts\SmartContractApiRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;
use function foo\func;

class GetSmartContractsLogic extends BusinessLogic
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
            'INPUT::Filters' => 'required',
            'INPUT::PerPage' => 'required',
            'INPUT::AuthorizationDTO' => 'required',
        ]);

        $filters = $this->getScope('INPUT::Filters');
        $perPage = $this->getScope('INPUT::PerPage');
        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        $smartContractsWithPagination = $this->smartContractRepository->getSmartContracts($filters, $perPage);

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }
        $smartContracts = $smartContractsWithPagination->items();
        array_walk($smartContracts, function ($smartContract) {
            $smartContract->buyer_user_id = encode($smartContract->buyer_user_id);
            $smartContract->vendor_id = encode($smartContract->vendor_id);
        });
        $smartContractsDetail = $this->smartContractApiRepository->getSmartContracts($smartContracts,$headers);

        return [
            'smart_contracts' => $smartContractsWithPagination,
            'smart_contracts_detail' => $smartContractsDetail
        ];
    }
}