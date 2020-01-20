<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Items\ActivateItemLogic;
use App\Http\Modules\V1\BusinessLogics\Items\CheckIfItemIsSmartContract;
use App\Http\Modules\V1\BusinessLogics\Items\DeactivateItemLogic;
use App\Http\Modules\V1\BusinessLogics\Items\GetItemLogic;
use App\Http\Modules\V1\BusinessLogics\Items\GetVendorSmartContractItemsLogic;
use App\Http\Modules\V1\BusinessLogics\Items\GetVendorTotalItemLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
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

    public function checkIfItemIsSmartContract($itemId)
    {
        $scopes = [
            'INPUT::ItemId' => $itemId
        ];

        $response = $this->execute([CheckIfItemIsSmartContract::class], $scopes);

        return $response[CheckIfItemIsSmartContract::class];
    }

    public function getItemsByVendorId($vendorId, AuthorizationDTO $authorizationDTO)
    {
        $scopes = [
            'INPUT::VendorId' => $vendorId,
            'INPUT::AuthorizationDTO' => $authorizationDTO
        ];

        $response = $this->execute([GetVendorSmartContractItemsLogic::class], $scopes);

        return $response[GetVendorSmartContractItemsLogic::class];
    }

    public function getVendorTotalItem($vendorId)
    {
        $scopes = [
            'INPUT::VendorId' => $vendorId,
        ];

        $response = $this->execute([GetVendorTotalItemLogic::class], $scopes);

        return $response[GetVendorTotalItemLogic::class];
    }
}