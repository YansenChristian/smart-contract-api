<?php


namespace App\Http\Controllers\V1;


use App\Exceptions\ApiValidationException;
use App\Http\Controllers\Controller;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
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

    public function getSmartContractVendors(Request $request, VendorService $vendorService)
    {
        $rules = [
            'limit' => 'required|int',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $result = $vendorService->getSmartContractVendors(
            $authorizationDTO,
            $request->get('limit'),
            $request->get('keyword', '')
        );
        return response()->json($result, 200);
    }

    public function postActivateVendors(Request $request, VendorService $vendorService)
    {
        $rules = [
            'vendors' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $vendors = $request->get('vendors');
        $vendorIds = array_column($vendors, 'id');
//        array_walk($vendorIds, function (&$vendorId) {
//            $vendorId = decode($vendorId);
//        });

        $vendorService->activateVendors($vendorIds, array_column($vendors, 'name'));

        return response()->json([
            'message' => 'The vendors: ['.implode(", ",array_column($vendors, 'id'))."] have been activated to be part(s) of Smart Contract"
        ]);
    }

    public function postDeactivateVendors(Request $request, VendorService $vendorService)
    {
        $rules = [
            'vendor_ids' => 'required|array'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $vendorIds = $request->get('vendor_ids');
//        array_walk($vendorIds, function (&$vendorId) {
//            $vendorId = decode($vendorId);
//        });

        $vendorService->deactivateVendors($vendorIds);

        return response()->json([
            'message' => 'The vendors: ['.implode(", ",$request->get('vendor_ids'))."] have been deactivated from being part(s) of Smart Contract"
        ]);
    }
}