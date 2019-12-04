<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Users\CheckIfVendorIsSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\Users\GetSmartContractVendorsLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Service;
use stdClass;

class VendorService extends Service
{
    public function checkIfVendorIsSmartContract(VendorDTO $vendorDTO)
    {
        $scopes = [
            'INPUT::VendorDTO' => $vendorDTO
        ];

        $response = $this->execute([
            CheckIfVendorIsSmartContractLogic::class
        ], $scopes);

        return $response[CheckIfVendorIsSmartContractLogic::class];
    }

    public function getSmartContractVendors(AuthorizationDTO $authorizationDTO, $perPage)
    {
        $scopes = [
            'INPUT::PerPage' => $perPage,
            'INPUT::AuthorizationDTO' => $authorizationDTO,
        ];

        $response = $this->execute([
           GetSmartContractVendorsLogic::class
        ], $scopes);

        $response = $response[GetSmartContractVendorsLogic::class];

        $vendors = $response['vendors'];
        $vendorsDetail = $response['vendors_detail'];
        $vendorIds = array_column($response['vendors_detail'], 'id');

        $vendors->getCollection()->transform(function ($value) use ($vendorIds, $vendorsDetail) {
            $vendorDetail = $vendorsDetail[array_search(encode($value->vendor_id), $vendorIds)];

            $newValue = new stdClass();
            $newValue->id = $vendorDetail['id'];
            $newValue->seller_name = $vendorDetail['seller_name'];
            $newValue->email = $vendorDetail['email'];
            $newValue->handphone = $vendorDetail['handphone'];
            $newValue->phone = $vendorDetail['phone'];
            $newValue->name = $vendorDetail['name'];
            $newValue->microsite_url = $vendorDetail['microsite_url'];
            $newValue->address = $vendorDetail['address'];
            $newValue->total_smart_contract_products = $vendorDetail['total_smart_contract_products'];
            $newValue->view_vendor_profile_link = $vendorDetail['view_vendor_profile_link'];
            $newValue->is_active = is_null($value->deleted_at) ? false : true;

            return $newValue;
        });

        return $vendors;
    }
}