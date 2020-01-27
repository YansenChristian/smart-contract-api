<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;

class CheckIfItemsAreSmartContractLogic extends BusinessLogic
{
    private $itemRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->itemRepository = new ItemRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::ItemIds' => 'required'
        ]);

        $itemIds = $this->getScope('INPUT::ItemIds');

        $itemIdsExist = [];
        foreach ($itemIds as $itemId) {
            $itemIdsExist[encode($itemId)] = false;
        }

        $existingItemIds = $this->itemRepository->getExistingItemIds($itemIds);
        foreach ($existingItemIds as $id) {
            if(is_null($id->deleted_at )) {
                $itemIdsExist[encode($id->item_id)] = true;
            }
        }

        $this->putScope('DB::ItemIdsExist', $itemIdsExist);
        return $itemIdsExist;
    }
}