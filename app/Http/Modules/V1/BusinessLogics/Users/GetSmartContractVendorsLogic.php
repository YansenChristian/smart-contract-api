<?php


namespace App\Http\Modules\V1\BusinessLogics\Users;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Users\UserApiRepository;
use App\Http\Modules\V1\Repositories\Database\Users\VendorRepository;

class GetSmartContractVendorsLogic extends BusinessLogic
{
    private $vendorRepository;
    private $userApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->userApiRepository = new UserApiRepository();
        $this->vendorRepository = new VendorRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::PerPage' => 'required',
            'INPUT::AuthorizationDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $perPage = $this->getScope('INPUT::PerPage');

        $vendors = $this->vendorRepository->getVendors($perPage);
        $vendorIds = array_column($vendors->items(), 'vendor_id');
        array_walk($vendorIds, function (&$vendorId) {
            $vendorId = encode($vendorId);
        });

        $vendorsDetail = $this->getVendorsDetail($authorizationDTO, $vendorIds);

        return [
            'vendors' => $vendors,
            'vendors_detail' => $vendorsDetail
        ];
    }

    private function getVendorsDetail($authorizationDTO, $vendorIds)
    {
        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $payloads = [
            'vendor_ids' => $vendorIds
        ];

        return $this->userApiRepository->getVendorsByIds($payloads, $headers);
    }
}