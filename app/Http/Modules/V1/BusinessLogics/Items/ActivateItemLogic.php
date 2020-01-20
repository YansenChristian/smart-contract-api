<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\Items\ItemLogDTO;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;

class ActivateItemLogic extends BusinessLogic
{
    private $itemRepository;
    private $logRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->itemRepository = new ItemRepository();
        $this->logRepository = new LogRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::ItemDTO' => 'required',
            'INPUT::SellerDTO' => 'required'
        ]);

        $itemDTO = $this->getScope('INPUT::ItemDTO');
        $sellerDTO = $this->getScope('INPUT::SellerDTO');
        $itemExists = $this->itemRepository->exists([
            'item_id' => $itemDTO->item_id
        ]);

        if($itemExists) {
            $itemData = ['deleted_at' => null];
            $conditions = ['item_id' => $itemDTO->item_id];

            $this->itemRepository->update($itemData, $conditions);
        } else {
            $this->itemRepository->add([
                'item_id' => $itemDTO->item_id,
                'vendor_id' => $sellerDTO->id
            ]);
        }

        $this->createLog();
        return;
    }

    private function createLog()
    {
        $sellerDTO = $this->getScope('INPUT::SellerDTO');
        $itemDTO = $this->getScope('INPUT::ItemDTO');

        $itemLogDTO = new ItemLogDTO();
        $itemLogDTO->item_id = $itemDTO->item_id;
        $itemLogDTO->seller_user_id = $sellerDTO->id;
        $itemLogDTO->action ='Activate';

        $this->logRepository->addItemLog($itemLogDTO->toArray());
    }
}