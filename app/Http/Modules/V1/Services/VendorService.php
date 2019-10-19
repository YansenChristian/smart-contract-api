<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Users\CheckIfVendorIsSmartContractLogic;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Service;

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
}