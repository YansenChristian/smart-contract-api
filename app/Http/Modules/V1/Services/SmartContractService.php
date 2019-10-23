<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\SmartContracts\CreateSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GenerateSmartContractSerialLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractsLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractCounterLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Service;

class SmartContractService extends Service
{
    public function getCounter()
    {
        $response = $this->execute([GetSmartContractCounterLogic::class]);
        return ($response[GetSmartContractCounterLogic::class]);
    }

    public function getSellerSmartContracts(AuthorizationDTO $authorizationDTO, $filters, $perPage)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::Filters' => $filters,
            'INPUT::PerPage' => $perPage
        ];

        $response = $this->execute([
            GetSellerSmartContractsLogic::class
        ], $scopes);

        # process data result
        $smartContracts = $response[GetSellerSmartContractsLogic::class];
        $smartContracts->getCollection()->transform(function ($value) {
            $newValue = new \stdClass();

            $newValue->smart_contract_serial = $value->smart_contract_serial;
            $newValue->order_date = date('d-m-Y', strtotime($value->created_at));
            $newValue->total_price = displayNumeric($value->total_price);
            $newValue->status = trans(SmartContractStatus::getByName($value->status)['description']);
            $newValue->buyer_name = $value->buyer_name;
            $newValue->view_smart_contract_detail_link = env('SELLER_PANEL_URL')
                .'#/smart_contracts/'
                .smartContractSerialToAlias($value->smart_contract_serial);

            if($value->status == SmartContractStatus::WAITING['name']) {
                $twoDaysAgo = strtotime('-2 days', strtotime('today'));
                $newValue->badge = (strtotime($value->created_at) > $twoDaysAgo)
                    ? trans('SmartContracts/recency.New')
                    : trans('SmartContracts/recency.Old');
            } else {
                $statusDetailParams = [
                    'on_going_order' => $value->on_going_order,
                    'total_order' => $value->total_order
                ];
                $newValue->status_detail = trans('SmartContracts/misc.status_detail', $statusDetailParams);
                $newValue->view_smart_contract_legal_link = env('SELLER_PANEL_URL')
                    .'#/legal?smart_contract_serial='
                    .smartContractSerialToAlias($value->smart_contract_serial);
            }

            return $newValue;
        });

        return $smartContracts;
    }

    public function createSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO, $orderDates)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::OrderDates' => $orderDates
        ];

        $response = $this->execute([
            GenerateSmartContractSerialLogic::class,
            CreateSmartContractLogic::class
        ], $scopes);
        return $response;
    }
}