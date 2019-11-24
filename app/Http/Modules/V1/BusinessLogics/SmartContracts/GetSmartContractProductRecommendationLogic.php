<?php


namespace App\Http\Modules\V1\BusinessLogics\SmartContracts;


use App\Http\Modules\V1\BusinessLogic;
use App\Http\Modules\V1\Repositories\API\Items\ItemApiRepository;
use App\Http\Modules\V1\Repositories\Database\SmartContracts\SmartContractRepository;

class GetSmartContractProductRecommendationLogic extends BusinessLogic
{
    private $smartContractRepository;
    private $itemApiRepository;

    public function __construct($scopes)
    {
        $this->scopes = $scopes;
        $this->smartContractRepository = new SmartContractRepository();
        $this->itemApiRepository = new ItemApiRepository();
    }

    /**
     * The main function of this Business logic
     * @return mixed
     */
    public function run()
    {
        $this->validateScopes([
            'INPUT::AuthorizationDTO' => 'required'
        ]);

        $authorizationDTO = $this->getScope('INPUT::AuthorizationDTO');

        $itemsTotalOrder = $this->smartContractRepository->getItemsTotalOrder();

        # Create list of encoded item's id
        $itemIds = array_column($itemsTotalOrder, 'item_id');
        array_walk($itemIds, function (&$itemId) {
             $itemId = encode($itemId);
        });

        $items = $this->getItemsData($itemIds, $authorizationDTO);
        $this->putScope('API::Items', $items);
        return $items;
    }

    private function getItemsData($itemIds, $authorizationDTO)
    {
        $headers = [];
        if(isset($authorizationDTO->bearer)) {
            $headers['Authorization'] = $authorizationDTO->bearer;
        }
        if(isset($authorizationDTO->access_token)) {
            $headers['x-access-token'] = $authorizationDTO->access_token;
        }

        $payloads = [
            'item_ids' => $itemIds
        ];

        return $this->itemApiRepository->getItemsByIds($payloads, $headers);
    }
}