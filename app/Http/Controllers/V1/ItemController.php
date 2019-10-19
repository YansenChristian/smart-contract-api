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
            'item_id' => 'required|string',
            'seller_id' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new \Exception($validator->getMessageBag());
        }

        $itemDTO = new ItemDTO();
        $itemDTO->setItemId(decode($request->get('item_id')));

        $sellerDTO = new SellerDTO();
        $sellerDTO->setSellerUserId(decode($request->get('seller_id')));

        $currentStatus = $itemService->toggleItemActivation($itemDTO, $sellerDTO);
        $message = ($currentStatus)
            ? "Successfully activate item: " . $request->get('item_id') . " as a Smart Contract's item!"
            : "Successfully deactivate item: " . $request->get('item_id') . " from being part of Smart Contract's items!";

        return response()->json([
            'message' => $message
        ], 200);
    }

    public function getCheckIfItemsAreSmartContract(Request $request,ItemService $itemService)
    {

        $rules = [
            'item_ids' => 'required|array'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $itemIds = array_map("decode", $request->get('item_ids'));
        dd($itemIds);
        $response = $itemService->checkIfItemsAreSmartContract($itemIds);

        return response()->json($response, 200);
    }
}