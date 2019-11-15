<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Orders\CheckIfOrderIsSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\Orders\CreateSmartContractOrdersLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\ApproveSmartContractRequestLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\CreateSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GenerateSmartContractSerialLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractDetailLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractsLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractCounterLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDetailDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Service;
use stdClass;

class SmartContractService extends Service
{
    public function getCounter(VendorDTO $vendorDTO)
    {
        $scopes = [
            'INPUT::VendorDTO' => $vendorDTO
        ];

        $response = $this->execute([GetSmartContractCounterLogic::class], $scopes);
        $counter = $response[GetSmartContractCounterLogic::class]->toArray();

        foreach (SmartContractStatus::getConstants() as $status) {
            if(!isset($counter[$status['name']])) {
                $counter[$status['name']] = 0;
            }
        }

        return $counter;
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

    public function getSellerSmartContractDetail(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::AuthorizationDTO' => $authorizationDTO
        ];

        $response = $this->execute([GetSellerSmartContractDetailLogic::class], $scopes);
        $response = $response[GetSellerSmartContractDetailLogic::class];

        $smartContractDetail = new stdClass();
        $smartContractDetail->smart_contract_serial = $response->smart_contract_serial;
        $smartContractDetail->order_date = date('d-m-Y', strtotime($response->created_at));
        $smartContractDetail->total_price = 'Rp ' . $response->total_price;
        $smartContractDetail->status = $response->status;
        $statusDetailParams = [
            'on_going_order' => $response->on_going_order,
            'total_order' => $response->total_order
        ];
        $smartContractDetail->status_detail = trans('SmartContracts/misc.status_detail', $statusDetailParams);
        $smartContractDetail->buyer = $response->buyer;
        $smartContractDetail->shipping_detail = $response->shipping_detail;
        $smartContractDetail->buyer_notes = $response->buyer_notes;
        $smartContractDetail->item = $response->item;

        $smartContractDetail->orders = $response->orders;
        foreach ($smartContractDetail->orders as &$order) {
            $order['print_delivery_order_link'] = env('SMART_CONTRACT_URL')
                . 'tokosaya/penjualan/delivery-order/'
                . $smartContractDetail->smart_contract_serial;

            $order['print_invoice_link'] = env('SMART_CONTRACT_URL')
                . 'tokosaya/penjualan/invoice/'
                . $smartContractDetail->smart_contract_serial;
        }

        $smartContractDetail->logs = [];
        foreach ($response->logs as $log) {
            $smartContractLog = new stdClass();
            $smartContractLog->user_name = $log->user_name;
            $smartContractLog->created_at = $log->created_at;
            $smartContractLog->status = $log->smart_contract_status;
            $smartContractLog->information = $log->information;

            $smartContractDetail->logs[] = $smartContractLog;
        }

        return $smartContractDetail;
    }

    public function createSmartContract(
        AuthorizationDTO $authorizationDTO,
        SmartContractDTO $smartContractDTO,
        $orderDates,
        $checkoutData
    )
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::OrderDates' => $orderDates,
            'INPUT::CheckoutData' => $checkoutData,
        ];

        $response = $this->execute([
            GenerateSmartContractSerialLogic::class,
            CreateSmartContractLogic::class,
            CreateSmartContractOrdersLogic::class,
        ], $scopes);
        return $response;
    }

    public function checkIfOrderIsSmartContract(SmartContractDetailDTO $smartContractDetailDTO)
    {
        $scopes = [
            'INPUT::SmartContractDetailDTO' => $smartContractDetailDTO
        ];

        $response = $this->execute([
            CheckIfOrderIsSmartContractLogic::class
        ], $scopes);

        return $response[CheckIfOrderIsSmartContractLogic::class];
    }

    public function approveSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO, SellerDTO $sellerDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::SellerDTO' => $sellerDTO
        ];

        $response = $this->execute([
            ApproveSmartContractRequestLogic::class
        ], $scopes);

        return $response[ApproveSmartContractRequestLogic::class];
    }
}