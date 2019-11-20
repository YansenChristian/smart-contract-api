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
        $vendorDTO = new VendorDTO();
        $vendorDTO->id = decode($vendor_id);

        $result = $vendorService->checkIfVendorIsSmartContract($vendorDTO);
        return response()->json($result, 200);
    }
}