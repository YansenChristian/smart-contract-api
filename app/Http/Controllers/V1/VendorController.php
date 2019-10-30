<?php


namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function getCheckVendorExists(Request $request, $vendor_id, VendorService $vendorService)
    {
        $payload = [
            'vendor_id' => $vendor_id
        ];

        $rules = [
            'vendor_id' => 'required|string'
        ];

        $validator = Validator::make($payload, $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $vendorDTO = new VendorDTO();
        $vendorDTO->id = decode($vendor_id);

        $result = $vendorService->checkIfVendorIsSmartContract($vendorDTO);
        return response()->json([
             $result
        ], 200);
    }
}