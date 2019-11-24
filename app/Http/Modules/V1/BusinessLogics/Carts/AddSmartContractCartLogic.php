<?php


namespace App\Http\Modules\V1\BusinessLogics\Carts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Carts\CartRepository;

class AddSmartContractCartLogic extends BusinessLogic
{
    private $cartRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->cartRepository = new CartRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::CartDTO' => 'required'
        ]);

        $cartDTO = $this->getScope('INPUT::CartDTO');

        $cart = $this->cartRepository->get($cartDTO->cart_id);

        if(isset($cart)) {
            if(!is_null($cart->deleted_at)) {
                $this->cartRepository->reviveCart($cartDTO->cart_id);
            }
            return encode($cartDTO->cart_id);
        }

        return encode($this->cartRepository->add($cartDTO->toArray()));
    }
}