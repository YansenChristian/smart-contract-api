<?php


namespace App\Http\Controllers\V1;


use App\Exceptions\ApiValidationException;
use App\Http\Controllers\Controller;
use App\Http\Modules\V1\DataTransferObjects\Carts\CartDTO;
use App\Http\Modules\V1\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getCheckIfCartIsSmartContract(Request $request, $cart_id, CartService $cartService)
    {
        $cartDTO = new CartDTO();
        $cartDTO->cart_id = decode($cart_id);

        $result = $cartService->checkIfCartIsSmartContract($cartDTO);
        return response()->json($result, 200);
    }

    public function postSmartContractCart(Request $request, CartService $cartService)
    {
        $rules = [
            'cart_id' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new ApiValidationException($validator->getMessageBag());
        }

        $cartDTO = new CartDTO();
        $cartDTO->cart_id = decode($request->get('cart_id'));

        $result = $cartService->add($cartDTO);
        return response()->json($result, 200);
    }

    public function deleteSmartContractCart(Request $request, $cart_id, CartService $cartService)
    {
        $cartDTO = new CartDTO();
        $cartDTO->cart_id = decode($cart_id);

        $result = $cartService->delete($cartDTO);
        return response()->json($result, 200);
    }
}