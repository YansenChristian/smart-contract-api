<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Carts\AddSmartContractCartLogic;
use App\Http\Modules\V1\BusinessLogics\Carts\CheckIfCartIsSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\Carts\DeleteSmartContractCartLogic;
use App\Http\Modules\V1\DataTransferObjects\Carts\CartDTO;
use App\Http\Modules\V1\Service;

class CartService extends Service
{
    public function checkIfCartIsSmartContract(CartDTO $cartDTO)
    {
        $scopes = [
            'INPUT::CartDTO' => $cartDTO
        ];

        $response = $this->execute([
            CheckIfCartIsSmartContractLogic::class
        ], $scopes);

        return $response[CheckIfCartIsSmartContractLogic::class];
    }

    public function add(CartDTO $cartDTO)
    {
        $scopes = [
            'INPUT::CartDTO' => $cartDTO
        ];

        try {
            $response = $this->execute([
                AddSmartContractCartLogic::class
            ], $scopes);
        } catch (\Exception $exception) {
            return "Cannot add the same cart_id multiple times!";
        }

        return $response[AddSmartContractCartLogic::class];
    }

    public function delete(CartDTO $cartDTO)
    {
        $scopes = [
            'INPUT::CartDTO' => $cartDTO
        ];

        $response = $this->execute([
            DeleteSmartContractCartLogic::class
        ], $scopes);

        return $response[DeleteSmartContractCartLogic::class];
    }
}