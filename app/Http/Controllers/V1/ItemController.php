<?php


namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
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
            'item_id' => 'required',
            'seller_id' => 'required'
        ];

        $payloads = [
            'item_id' => $request->get('item_id'),
            'seller_id' => $request->get('seller_id')
        ];

        $validator = Validator::make($payloads, $rules);
        if ($validator->fails()) {
            throw new \Exception($validator->getMessageBag());
        }

        $itemDTO = new ItemDTO();
        $itemDTO->setItemId($payloads['item_id']);

        $sellerDTO = new SellerDTO();
        $sellerDTO->setSellerUserId($payloads['seller_id']);

        $currentStatus = $itemService->toggleItemActivation($itemDTO, $sellerDTO);
        $message = ($currentStatus)
            ? "Successfully activate item: " . $payloads['item_id'] . " as a Smart Contract's item!"
            : "Successfully deactivate item: " . $payloads['item_id'] . " from being part of Smart Contract's items!";

        return response()->json([
            'message' => $message
        ], 200);
    }

    public function getCheckIfItemsAreSmartContract(Request $request,ItemService $itemService)
    {
        $payloads = [
            'item_ids' => $request->get('item_ids')
        ];

        $rules = [
            'item_ids' => 'required|array'
        ];

        $validator = Validator::make($payloads, $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $response = $itemService->checkIfItemsAreSmartContract($payloads['item_ids']);

        return response()->json($response, 200);
    }
}