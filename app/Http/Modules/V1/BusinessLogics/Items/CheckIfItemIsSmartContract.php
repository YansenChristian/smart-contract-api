<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;
use App\Http\Modules\V1\Repositories\Database\Users\VendorRepository;

class CheckIfItemIsSmartContract extends BusinessLogic
{
    private $itemRepository;
    private $vendorRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->itemRepository = new ItemRepository();
        $this->vendorRepository = new VendorRepository();
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
        if(!is_null($item) && is_null($item->deleted_at)) {
            $isSmartContractItem = $this->vendorRepository->vendorExists(['vendor_id' => $item->vendor_id]);
        } else {
            $isSmartContractItem = false;
        }

        $this->putScope('DB::ItemIdExists', $isSmartContractItem);
        return $isSmartContractItem;
    }
}