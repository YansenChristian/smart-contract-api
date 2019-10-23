<?php


namespace App\Http\Controllers\V1;

use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\Enumerations\SmartContracts\SmartContractStatus;
use App\Http\Modules\V1\Services\SmartContractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SmartContractController extends Controller
{
    public function getCounter(Request $request, SmartContractService $smartContractService)
    {
        $counters = $smartContractService->getCounter();
        return response()->json([
            $counters
        ], 200);
    }

    public function getSmartContracts(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'user_id' => 'required|string',
            'role' => 'required|string|in:Admin,Seller,Buyer',
            'page' => 'int',
            'limit' => 'int'
        ];

        if($request->has('smart_contract_serial')) {
            $rules['smart_contract_serial'] = 'required|string';
        }

        if($request->has('status')) {
            $rules['status'] = 'required|string|in:WAITING,APPROVED,REJECTED,IN_PROGRESS,CANCELED,ENDED';
        }

        if($request->has('start_date') || $request->has('end_date')) {
            $rules['start_date'] = 'required|date_format:Y-m-d';
            $rules['end_date'] = 'required|date_format:Y-m-d';
        }

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        $authorizationDTO->bearer = $request->header('authorization');

        $perPage = $request->get('limit', 10);

        if($request->has('smart_contract_serial')) {
            $filters['smart_contract_serial'] = $request->get('smart_contract_serial');
        }
        if($request->has('status')) {
            $filters['smart_contract_status_id'] = SmartContractStatus::getByName(
                $request->get('status')
            )['id'];
        }

        switch ($request->get('role')) {
            case 'Buyer':
                $filters['buyer_user_id'] = decode($request->get('user_id'));
                $response = '';
                break;
            case 'Seller':
                $filters['vendor_id'] = decode($request->get('user_id'));
                $response = $smartContractService->getSellerSmartContracts($authorizationDTO, $filters, $perPage);
                break;
            default:
                $response = '';
                break;
        }

        return response()->json($response, 200);
    }

    public function postCreateSmartContract(Request $request, SmartContractService $smartContractService)
    {
        $rules = [
            'buyer_id' => 'required|string',
            'vendor_id' => 'required|string',
            'total_order' => 'required|int',
            'order_dates' => 'required|array',
            'order_dates.*' => 'date_format:d-m-Y'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->vendor_id = $request->get('vendor_id');
        $smartContractDTO->buyer_user_id = $request->get('buyer_id');
        $smartContractDTO->total_order = $request->get('total_order');

        $authorizationDTO = new AuthorizationDTO();
        $authorizationDTO->bearer = $request->header('authorization');

        $orderDates = $request->get('order_dates');

        $smartContractService->createSmartContract($authorizationDTO, $smartContractDTO, $orderDates);

        return response()->json(null, 201);
    }
}