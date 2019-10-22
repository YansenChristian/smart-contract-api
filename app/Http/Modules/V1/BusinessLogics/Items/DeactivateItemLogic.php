<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\DataTransferObjects\Items\ItemLogDTO;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;
use App\Http\Modules\V1\Repositories\Database\Logs\LogRepository;

class DeactivateItemLogic extends BusinessLogic
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
        $itemExists = $this->itemRepository->exists([
            'item_id' => $itemDTO->item_id
        ]);

        if($itemExists) {
            $this->itemRepository->softDelete($itemDTO->item_id);
        }

        $this->createLog();
    }

    public function createLog()
    {
        $sellerDTO = $this->getScope('INPUT::SellerDTO');
        $itemDTO = $this->getScope('INPUT::ItemDTO');

        $itemLogDTO = new ItemLogDTO();
        $itemLogDTO->item_id = $itemDTO->item_id;
        $itemLogDTO->seller_user_id = $sellerDTO->seller_user_id;
        $itemLogDTO->action = 'Deactivate';

        $this->logRepository->addItemLog($itemLogDTO->toArray());
    }
}