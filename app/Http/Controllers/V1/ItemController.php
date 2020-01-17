<?php


namespace App\Http\Controllers\V1;


use App\Exceptions\ApiValidationException;
use App\Http\Controllers\Controller;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\Items\ItemDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function postToggleItemActivation(Request $request, ItemService $itemService)
    {
        $rules = [
            'item_id' => 'required|string',
            'seller_id' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new ApiValidationException($validator->getMessageBag());
        }

        $itemDTO = new ItemDTO();
        $itemDTO->item_id = ($request->get('source') == 'Seller Panel')
            ? $request->get('item_id')
            : decode($request->get('item_id'));

        $sellerDTO = new SellerDTO();
        $sellerDTO->id = ($request->get('source') == 'Seller Panel')
            ? $request->get('seller_id')
            : decode($request->get('seller_id'));

        $currentStatus = $itemService->toggleItemActivation($itemDTO, $sellerDTO);
        $message = ($currentStatus)
            ? "Successfully activate item: " . $request->get('item_id') . " as a Smart Contract's item!"
            : "Successfully deactivate item: " . $request->get('item_id') . " from being part of Smart Contract's items!";

        return response()->json([
            'message' => $message
        ], 200);
    }

    public function postCheckIfItemsAreSmartContract(Request $request,ItemService $itemService)
    {

        $rules = [
            'item_ids' => 'required|array'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $itemIds = array_map("decode", $request->get('item_ids'));

        $response = $itemService->checkIfItemsAreSmartContract($itemIds);

        return response()->json($response, 200);
    }

    public function getCheckIfItemIsSmartContract(Request $request, ItemService $itemService)
    {
        $rules = [
            'item_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $response = $itemService->checkIfItemIsSmartContract($request->get('item_id'));

        return response()->json($response, 200);
    }

    public function getItemsByVendorId(Request $request, ItemService $itemService)
    {
        $rules = [
            'vendor_id' => 'required'
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

        $response = $itemService->getItemsByVendorId($request->get('vendor_id'), $authorizationDTO);

        return response()->json($response, 200);
    }
}