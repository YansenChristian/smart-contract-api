<?php


namespace App\Http\Controllers\V1;

use App\Exceptions\ApiValidationException;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDetailDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractLogDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\VendorDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Services\SmartContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SmartContractController extends Controller
{
    public function getCounter(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'vendor_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $vendorDTO = new VendorDTO();
        $vendorDTO->id = $request->get('vendor_id');

        $counters = $smartContractService->getSellerCounter($vendorDTO);
        return response()->json($counters, 200);
    }

    public function getSmartContracts(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'vendor_id' => 'string',
            'user_id' => 'string',
            'role' => 'required|string|in:Admin,Seller,Buyer',
            'page' => 'int',
            'limit' => 'int'
        ];

        if($request->get('role') != 'Admin') {
            $rules['vendor_id'] .= '|required_without:user_id';
            $rules['user_id'] .= '|required_without:vendor_id';
        }

        if($request->has('smart_contract_serial')) {
            $rules['smart_contract_serial'] = 'required|string';
        }

        if($request->has('status')) {
            $rules['status'] = 'required|string|in:WAITING,APPROVED,REJECTED,IN_PROGRESS,CANCELED,ENDED';

            if($request->has('filter')) {
                switch ($request->get('status')) {
                    case 'WAITING':
                        $rules['filter'] = 'required|string|in:new,old';
                        break;
                }
            }
        }

        if($request->has('start_date') || $request->has('end_date')) {
            $rules['start_date'] = 'required|date_format:Y-m-d';
            $rules['end_date'] = 'required|date_format:Y-m-d';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $perPage = $request->get('limit', 10);
        $filters = [];

        if($request->has('smart_contract_serial')) {
            $filters[] = [
                'column' => 'smart_contracts.smart_contract_serial',
                'operator' => '=',
                'value' => $request->get('smart_contract_serial')
            ];
        }

        if($request->has('start_date') || $request->has('end_date')) {
            $filters[] = [
                'column' => 'smart_contracts.created_at',
                'operator' => 'Between',
                'value' => [
                    $request->get('start_date'),
                    $request->get('end_date'),
                ]
            ];
        }

        if($request->has('status')) {
            $filters[] = [
                'column' => 'smart_contracts.smart_contract_status_id',
                'operator' => '=',
                'value' => SmartContractStatus::getByName($request->get('status'))['id']
            ];

            if($request->has('filter')) {
                switch ($request->get('status')) {
                    case 'WAITING':
                        switch ($request->get('filter')) {
                            case 'old':
                                $filters[] = [
                                    'column' => 'smart_contracts.created_at',
                                    'operator' => '<=',
                                    'value' => strtotime('-2 days', strtotime('today'))
                                ];
                                break;
                            case 'new':
                                $filters[] = [
                                    'column' => 'smart_contracts.created_at',
                                    'operator' => '>',
                                    'value' => strtotime('-2 days', strtotime('today'))
                                ];
                                break;
                        }
                        break;
                }
            }
        }

        switch ($request->get('role', '')) {
            case 'Buyer':
                $filters[] = [
                    'column' => 'smart_contracts.buyer_user_id',
                    'operator' => '=',
                    'value' => $request->get('user_id')
                ];

                $response = $smartContractService->getBuyerSmartContracts($authorizationDTO,$filters, $perPage);
                break;
            case 'Seller':
                $filters[] = [
                    'column' => 'smart_contracts.vendor_id',
                    'operator' => '=',
                    'value' => $request->get('vendor_id')
                ];

                $response = $smartContractService->getSellerSmartContracts($authorizationDTO, $filters, $perPage);
                break;
            default:
                $response = $smartContractService->getSmartContracts($authorizationDTO, $filters, $perPage);
                break;
        }

        return response()->json($response, 200);
    }

    public function getSmartContractDetail(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        switch ($request->get('role', '')) {
            case 'Buyer':
                $response = $smartContractService->getBuyerSmartContractDetail($authorizationDTO, $smartContractDTO);
                break;
            case 'Seller':
                $response = $smartContractService->getSellerSmartContractDetail($authorizationDTO, $smartContractDTO);
                break;
            default:
                $response = $smartContractService->getSmartContractDetail($authorizationDTO, $smartContractDTO);
                break;
        }

        return response()->json($response, 200);
    }

    public function postCreateSmartContract(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'total_order' => 'required|int',
            'order_dates' => 'required|array',
            'order_dates.*' => 'date_format:d-m-Y'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->vendor_id = $request->get('cart_data')[0]['vendor_id'];
        $smartContractDTO->buyer_user_id = decode($request->get('user_id'));
        $smartContractDTO->payment_method_id = $request->get('payment_id');
        $smartContractDTO->smart_contract_status = SmartContractStatus::WAITING;
        $smartContractDTO->total_order = $request->get('total_order');
        $smartContractDTO->buyer_notes = isset($request->get('cart_data')[0]['comment'])
            ? $request->get('cart_data')[0]['comment']
            : '';
        $smartContractDTO->total_price = $request->get('grand_total') * $request->get('total_order');

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $orderDates = $request->get('order_dates');
        $checkoutData = $request->all();

        $smartContractService->createSmartContract(
            $authorizationDTO,
            $smartContractDTO,
            $orderDates,
            $checkoutData
        );

        return response()->json(null, 201);
    }

    public function getCheckIfOrderIsSmartContract(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'order_serial' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $smartContractDetailDTO = new SmartContractDetailDTO();
        $smartContractDetailDTO->order_serial = $request->get('order_serial');

        $response = $smartContractService->checkIfOrderIsSmartContract($smartContractDetailDTO);

        return response()->json($response, 200);
    }

    public function postApproveSmartContract(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);
        $smartContractDTO->smart_contract_status = SmartContractStatus::APPROVED;

        $sellerDTO = new SellerDTO();
        $sellerDTO->id = ($request->get('source') == 'Seller Panel')
            ? $request->get('user_id')
            : decode($request->get('user_id'));

        $smartContractService->approveSmartContract($authorizationDTO, $smartContractDTO, $sellerDTO);

        $response = trans(
            'SmartContracts/update_status_response.' . SmartContractStatus::APPROVED['name'],
            ['smart_contract_serial' => $smartContractDTO->smart_contract_serial]
        );
        return response()->json($response, 200);
    }

    public function postRejectSmartContract(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);
        $smartContractDTO->smart_contract_status = SmartContractStatus::REJECTED;

        $sellerDTO = new SellerDTO();
        $sellerDTO->id = ($request->get('source') == 'Seller Panel')
            ? $request->get('user_id')
            : decode($request->get('user_id'));

        $smartContractService->rejectSmartContract($authorizationDTO, $smartContractDTO, $sellerDTO);

        $response = trans(
            'SmartContracts/update_status_response.' . SmartContractStatus::REJECTED['name'],
            ['smart_contract_serial' => $smartContractDTO->smart_contract_serial]
        );
        return response()->json($response, 200);
    }

    public function getSmartContractProductRecommendation(Request $request, SmartContractService $smartContractService)
    {
        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $response = $smartContractService->getSmartContractProductRecommendation($authorizationDTO);
        return response()->json($response, 200);
    }

    public function postCancelSmartContract(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $rules = [
            'notes' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);
        $smartContractDTO->smart_contract_status = SmartContractStatus::CANCELED;

        $smartContractLogDTO = new SmartContractLogDTO();
        $smartContractLogDTO->information = 'Smart Contract has been canceled';
        if($request->get('notes') == 'Auto Reject Unpaid Order') {
            $smartContractLogDTO->information = 'SmartContracts/status_detail.canceled_by_unpaid_order';
        } else if ($request->get('notes') == 'Auto Reject Unprocessed Order') {
            $smartContractLogDTO->information = 'SmartContracts/status_detail.canceled_by_unprocessed_order';
        }

        $smartContractService->cancelSmartContract($authorizationDTO, $smartContractDTO, $smartContractLogDTO);

        $response = trans(
            'SmartContracts/update_status_response.' . SmartContractStatus::CANCELED['name'],
            ['smart_contract_serial' => $smartContractDTO->smart_contract_serial]
        );
        return response()->json($response, 200);
    }

    public function getSmartContractForOrderSerial(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'order_serial' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new ApiValidationException($validator->getMessageBag());
        }

        $smartContractDetailDTO = new SmartContractDetailDTO();
        $smartContractDetailDTO->order_serial = $request->get('order_serial');

        $result = $smartContractService->getSmartContractByOrderSerial($smartContractDetailDTO);

        return response()->json($result, 200);
    }

    public function postProcessSmartContract(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);
        $smartContractDTO->smart_contract_status = SmartContractStatus::IN_PROGRESS;

        $smartContractService->processSmartContract($authorizationDTO, $smartContractDTO);

        $response = trans(
            'SmartContracts/update_status_response.' . SmartContractStatus::IN_PROGRESS['name'],
            ['smart_contract_serial' => $smartContractDTO->smart_contract_serial]
        );
        return response()->json($response, 200);
    }

    public function postCompleteSmartContract(Request $request, $smart_contract_serial, SmartContractService $smartContractService)
    {
        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = smartContractSerialToOriginal($smart_contract_serial);
        $smartContractDTO->smart_contract_status = SmartContractStatus::ENDED;

        $smartContractService->completeSmartContract($authorizationDTO, $smartContractDTO);

        $response = trans(
            'SmartContracts/update_status_response.' . SmartContractStatus::ENDED['name'],
            ['smart_contract_serial' => $smartContractDTO->smart_contract_serial]
        );
        return response()->json($response, 200);
    }
}
