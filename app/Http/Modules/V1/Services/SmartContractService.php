<?php


namespace App\Http\Modules\V1\Services;


use App\Http\Modules\V1\BusinessLogics\Orders\CheckIfOrderIsSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\Orders\CreateSmartContractOrdersLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\ApproveSmartContractRequestLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\CancelSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\CompleteSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\CreateSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GenerateSmartContractSerialLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetBuyerSmartContractDetailLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetBuyerSmartContractsLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractDetailLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractsLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractByOrderSerialLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSellerSmartContractCounterLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractCounterLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractDetailLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractProductRecommendationLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\GetSmartContractsLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\ProcessSmartContractLogic;
use App\Http\Modules\V1\BusinessLogics\SmartContracts\RejectSmartContractRequestLogic;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDetailDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Service;
use Illuminate\Support\Facades\DB;
use stdClass;

class SmartContractService extends Service
{
    public function getSellerCounter(VendorDTO $vendorDTO)
    {
        $scopes = [
            'INPUT::VendorDTO' => $vendorDTO
        ];

        $response = $this->execute([GetSellerSmartContractCounterLogic::class], $scopes);
        $counter = $response[GetSellerSmartContractCounterLogic::class]->toArray();

        foreach (SmartContractStatus::getConstants() as $status) {
            if(!isset($counter[$status['name']])) {
                $counter[$status['name']] = 0;
            }
        }

        return $counter;
    }

    public function getCounter()
    {
        $response = $this->execute([GetSmartContractCounterLogic::class], []);
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
                .'#/transactions/smart-contract/detail/'
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
                    .'#/transactions/smart-contract/legal/'
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
            $smartContractLog->information = trans($log->information);

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

    public function rejectSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO, SellerDTO $sellerDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::SellerDTO' => $sellerDTO
        ];

        $response = $this->execute([
            RejectSmartContractRequestLogic::class
        ], $scopes);

        return $response[RejectSmartContractRequestLogic::class];
    }

    public function getSmartContractProductRecommendation(AuthorizationDTO $authorizationDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO
        ];

        $response = $this->execute([
            GetSmartContractProductRecommendationLogic::class
        ], $scopes);

        return $response[GetSmartContractProductRecommendationLogic::class];
    }

    public function getBuyerSmartContracts($authorizationDTO, $filters, $perPage)
    {
        $scopes = [
            'INPUT::Filters' => $filters,
            'INPUT::PerPage' => $perPage,
            'INPUT::AuthorizationDTO' => $authorizationDTO,
        ];

        $response = $this->execute([
            GetBuyerSmartContractsLogic::class
        ], $scopes);

        $smartContracts = $response[GetBuyerSmartContractsLogic::class];

        $smartContracts->getCollection()->transform(function ($smartContract) {
            $newValue = new stdClass();

            $newValue->smart_contract_serial = $smartContract->smart_contract_serial;
            $newValue->status_detail = 'Order '.$smartContract->on_going_order.' of '.$smartContract->total_order;
            $newValue->status = $smartContract->status;

            $nextOrderDate = getOrderDateByOrderSerial($smartContract->order_serial_of_next_order);
            $nextOrderDate = date('d F Y, H:i' ,strtotime($nextOrderDate)) . ' WIB';
            $newValue->next_order_payment_due_date = $nextOrderDate;
            $newValue->view_smart_contract_detail_link = env('WEBSITE_URL').'#/smart_contracts/'.smartContractSerialToAlias($smartContract->smart_contract_serial);

            if($smartContract->on_going_order != $smartContract->total_order) {
                $newValue->confirm_next_order_payment_link =  env('API_CORE_URL').'api/v1/payment/'.orderSerialToAlias($smartContract->order_serial_of_next_order).'/confirm';
            }

            return $newValue;
        });

        return $smartContracts;
    }

    public function getBuyerSmartContractDetail(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::SmartContractDTO' =>  $smartContractDTO,
            'INPUT::AuthorizationDTO' => $authorizationDTO,
        ];

        $response = $this->execute([
            GetBuyerSmartContractDetailLogic::class
        ], $scopes);

        $smartContract = $response[GetBuyerSmartContractDetailLogic::class];

        $smartContract['total_price'] = displayNumeric($smartContract['total_price']);
        $smartContract['id'] = encode($smartContract['id']);
        $smartContract['buyer_user_id'] = encode($smartContract['buyer_user_id']);

        if($smartContract['on_going_order'] == $smartContract['total_order']) {
            $smartContract['view_cashback_voucher_link'] = env('WEBSITE_URL')."ralalipoin?tab=my-voucher";
        }

        return $smartContract;
    }

    public function getSmartContracts(AuthorizationDTO $authorizationDTO, $filters, $perPage)
    {
        $scopes = [
            'INPUT::Filters' => $filters,
            'INPUT::PerPage' => $perPage,
            'INPUT::AuthorizationDTO' => $authorizationDTO,
        ];

        $response = $this->execute([
            GetSmartContractsLogic::class
        ], $scopes);

        $response = $response[GetSmartContractsLogic::class];

        $smartContractsDetail = $response['smart_contracts_detail'];
        $orderSerials = array_column($response['smart_contracts_detail'], 'order_serial');

        $response['smart_contracts']->getCollection()->transform(function ($smartContract) use ($smartContractsDetail, $orderSerials) {
            $smartContractDetail = $smartContractsDetail[array_search($smartContract->order_serial, $orderSerials)];

            $smartContract->payment_method = $smartContractDetail['payment_method'];
            $smartContract->buyer = $smartContractDetail['buyer'];
            $smartContract->vendor = $smartContractDetail['vendor'];
            $smartContract->total_price = displayNumeric($smartContract->total_price);
            $smartContract->view_smart_contract_detail_link = env('CMS_URL')
                .'admin-cp/smart-contract/'
                .smartContractSerialToAlias($smartContract->smart_contract_serial);

            if($smartContract->status != SmartContractStatus::WAITING['name']) {
                $smartContract->view_smart_contract_legal_link = env('SELLER_PANEL_URL')
                    .'smart-contracts/v1/legals?smart_contract_serial='
                    .smartContractSerialToAlias($smartContract->smart_contract_serial);
            }

            unset($smartContract->buyer_user_id);
            unset($smartContract->vendor_id);

            return $smartContract;
        });

        return $response['smart_contracts'];
    }

    public function getSmartContractDetail(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::AuthorizationDTO' => $authorizationDTO
        ];

        $response = $this->execute([
            GetSmartContractDetailLogic::class
        ], $scopes);

        $response = $response[GetSmartContractDetailLogic::class];

        $formattedSmartContractDetail = $response['smart_contract_detail'];
        $formattedSmartContractDetail['smart_contract_serial'] = $response['smart_contract']->smart_contract_serial;
        $formattedSmartContractDetail['status'] = $response['smart_contract']->status;
        $formattedSmartContractDetail['status_detail'] = 'Order '.$response['smart_contract']->on_going_order.' of '.$response['smart_contract']->total_order;
        $formattedSmartContractDetail['total_price'] = displayNumeric($response['smart_contract']->total_price);
        $formattedSmartContractDetail['start_date'] = date('d F Y H:i:s', strtotime($response['smart_contract']->created_at));

        $formattedSmartContractDetail['logs'] = [];
        foreach ($response['logs'] as $log) {
            $newValue = [];
            $newValue['status'] = $log->smart_contract_status;
            $newValue['information'] = trans($log->information);
            $newValue['created_at'] = date('d F Y H:i:s', strtotime($log->created_at));

            $formattedSmartContractDetail['logs'][] = $newValue;
        }

        return $formattedSmartContractDetail;
    }

    public function cancelSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO, SmartContractLogDTO $smartContractLogDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
            'INPUT::SmartContractLogDTO' => $smartContractLogDTO,
        ];

        $response = $this->execute([
            CancelSmartContractLogic::class
        ], $scopes);

        return $response[CancelSmartContractLogic::class];
    }

    public function getSmartContractByOrderSerial(SmartContractDetailDTO $smartContractDetailDTO)
    {
        $scopes = [
            'INPUT::SmartContractDetailDTO' => $smartContractDetailDTO,
        ];

        $response = $this->execute([
            GetSmartContractByOrderSerialLogic::class
        ], $scopes);

        return $response[GetSmartContractByOrderSerialLogic::class];
    }

    public function processSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
        ];

        $response = $this->execute([
            ProcessSmartContractLogic::class
        ], $scopes);

        return $response[ProcessSmartContractLogic::class];
    }

    public function completeSmartContract(AuthorizationDTO $authorizationDTO, SmartContractDTO $smartContractDTO)
    {
        $scopes = [
            'INPUT::AuthorizationDTO' => $authorizationDTO,
            'INPUT::SmartContractDTO' => $smartContractDTO,
        ];

        $response = $this->execute([
            CompleteSmartContractLogic::class
        ], $scopes);

        return $response[CompleteSmartContractLogic::class];
    }
}