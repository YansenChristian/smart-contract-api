<?php


namespace App\Http\Modules\V1\Repositories\Database\Carts;


use Illuminate\Support\Facades\DB;

class CartRepository
{
    public function cartExists($cartId)
    {
        return DB::table('smart_contract_carts')
            ->where('cart_id', '=', $cartId)
            ->whereNull('deleted_at')
            ->exists();
    }

    public function add(array $cart)
    {
        return DB::table('smart_contract_carts')
            ->insertGetId($cart);
    }

    public function delete($cartId)
    {
        return DB::table('smart_contract_carts')
            ->where('cart_id', '=', $cartId)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }
}