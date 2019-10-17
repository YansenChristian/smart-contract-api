<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Items\ActivateItemLogic;
use App\Http\Modules\V1\BusinessLogics\Items\DeactivateItemLogic;
use App\Http\Modules\V1\BusinessLogics\Items\GetItemLogic;
use App\Http\Modules\V1\DataTransferObjects\Items\ItemDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\BusinessLogics\Items\CheckIfItemsAreSmartContractLogic;
use App\Http\Modules\V1\Service;

class ItemService extends Service
{
    public function toggleItemActivation(ItemDTO $itemDTO, SellerDTO $sellerDTO)
    {
        $scopes = [
            'INPUT::ItemDTO' => $itemDTO,
            'INPUT::SellerDTO' => $sellerDTO
        ];

        $result = $this->execute([GetItemLogic::class], $scopes);
        $item = $result->get(GetItemLogic::class);

        if(empty($item) || !is_null($item->deleted_at)) {
            $this->execute([ActivateItemLogic::class]);
            return true;
        } else {
            $this->execute([DeactivateItemLogic::class]);
            return false;
        }
    }

    public function checkIfItemsAreSmartContract($itemIds)
    {
        $scopes = [
            'INPUT::ItemIds' => $itemIds
        ];

        $response = $this->execute([CheckIfItemsAreSmartContractLogic::class], $scopes);

        return $response[CheckIfItemsAreSmartContractLogic::class];
    }
}