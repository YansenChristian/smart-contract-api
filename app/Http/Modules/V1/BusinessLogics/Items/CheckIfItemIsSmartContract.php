<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;

class CheckIfItemIsSmartContract extends BusinessLogic
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
            'INPUT::ItemId' => 'required'
        ]);

        $itemId = $this->getScope('INPUT::ItemId');

        $item = $this->itemRepository->getById($itemId);
        $isSmartContractItem = !is_null($item);

        $this->putScope('DB::ItemIdExists', $isSmartContractItem);
        return $isSmartContractItem;
    }
}