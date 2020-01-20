<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;

class GetVendorTotalItemLogic extends BusinessLogic
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
            'INPUT::VendorId' => 'required'
        ]);

        $vendorId = $this->getScope('INPUT::VendorId');
        return $this->itemRepository->getVendorTotalItem($vendorId);
    }
}