<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Items\ItemApiRepository;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;

class GetVendorSmartContractItemsLogic extends BusinessLogic
{
    private $itemRepository;
    private $itemApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->itemRepository = new ItemRepository();
        $this->itemApiRepository = new ItemApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::VendorId' => 'required',
            'INPUT::AuthorizationDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');
        $payloads = [
            'vendor_id' => $this->getScope('INPUT::VendorId')
        ];

        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $items = $this->itemApiRepository->getItemsByVendorId($payloads, $headers);
        $itemIds = array_column($items, 'id');

        $existingItems = $this->itemRepository->getExistingItems($itemIds);
        $existingItems->getCollection()->transform(function ($existingItem) use ($items, $itemIds){
            $index = array_search($existingItem->item_id, $itemIds);
            $items[$index]['is_smart_contract'] = is_null($existingItem->deleted_at);
            $smartContractItem = $items[$index];
            return $smartContractItem;
        });

        return $existingItems;
    }
}