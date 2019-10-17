<?php


namespace App\Http\Modules\V1\BusinessLogics\Items;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\Database\Items\ItemRepository;

class GetItemLogic extends BusinessLogic
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
            'INPUT::ItemDTO' => 'required'
        ]);

        $itemDTO = $this->getScope('INPUT::ItemDTO');
        $item = $this->itemRepository->getById($itemDTO->getItemId());

        $this->putScope('DB::Item', $item);
        return $item;
    }
}