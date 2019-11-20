<?php


namespace App\Http\Modules\V1\BusinessLogics\Carts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Carts\CartRepository;

class CheckIfCartIsSmartContractLogic extends BusinessLogic
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
        return $this->cartRepository->cartExists($cartDTO->cart_id);
    }
}