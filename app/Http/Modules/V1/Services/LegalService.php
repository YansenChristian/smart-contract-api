<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Legals\GetLegalContentLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\Service;
use stdClass;

class LegalService extends Service
{
    public function getContent(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO
        ];

        $response = $this->execute([
            GetLegalContentLogic::class
        ], $scopes);

        $legalContent = $response[GetLegalContentLogic::class];

        $newValue = new stdClass();
        $newValue->item_name = $legalContent->item_name;
        $newValue->quantity = $legalContent->quantity;
        $newValue->shipping_price = $legalContent->shipping_price;
        $newValue->shipping_address = $legalContent->shipping_address;
        $newValue->order_date = date('j F Y', strtotime($legalContent->order_date));
        $newValue->smart_contract_serial = $smartContractDTO->smart_contract_serial;
        $newValue->smart_contract_details = [];

        $newValue->total_price = 0;
        foreach ($legalContent->order_serials as $orderSerial) {
            $smartContractDetail = new stdClass();
            $smartContractDetail->order_serial = $orderSerial;
            $smartContractDetail->order_date = getOrderDateByOrderSerial($orderSerial);
            $smartContractDetail->subtotal_price = displayNumeric($legalContent->total_price);
            $newValue->total_price += $legalContent->total_price;

            $newValue->smart_contract_details[] = $smartContractDetail;
        }
        $newValue->total_price = displayNumeric($newValue->total_price);

        return $newValue;
    }
}