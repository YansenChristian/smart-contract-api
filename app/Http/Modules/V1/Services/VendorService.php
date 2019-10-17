<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Users\CheckIfVendorIsSmartContractLogic;
use App\Http\Modules\V1\Service;

class VendorService extends Service
{
    public function checkIfVendorIsSmartContract($payloads)
    {
        $scopes = [
            'INPUT::VendorId' => $payloads['vendor_id']
        ];

        $response = $this->execute([
            CheckIfVendorIsSmartContractLogic::class
        ], $scopes);

        return $response[CheckIfVendorIsSmartContractLogic::class];
    }
}