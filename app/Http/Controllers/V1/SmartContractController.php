<?php


namespace App\Http\Controllers\V1;

use App\Http\Modules\V1\DataTransferObjects\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\UserDTO;
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
        $payloads = [
            'user_id' => $request->get('user_id'),
            'role' => $request->get('role'),
            'smart_contract_serial' => $request->get('smart_contract_serial', null),
            'status' => $request->get('status', null),
            'start_date' => $request->get('start_date', null),
            'end_date' => $request->get('end_date', null),
            'page' => $request->get('page', 1),
            'limit' => $request->get('page', 10),
        ];

        $rules = [
            'user_id' => 'required',
            'role' => 'required|in:Admin,Seller,Buyer',
            'page' => 'int',
            'limit' => 'int'
        ];

        if($request->has('status')) {
            $rules['status'] = 'in:WAITING,APPROVED,REJECTED,IN_PROGRESS,CANCELED,ENDED';
        }

        if($request->has('start_date') || $request->has('end_date')) {
            $rules['start_date'] = 'required|date_format:Y-m-d|';
            $rules['end_date'] = 'required|date_format:Y-m-d';
        }

        $validator = Validator::make($payloads, $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        $authorizationDTO->setBearerToken($request->header('authorization'));

        $perPage = $request->get('limit', 10);

        $filters['user_id'] = $request->get('user_id');
        if($request->has('smart_contract_serial')) {
            $filters['smart_contract_serial'] = $request->get('smart_contract_serial');
        }
        if(isset($payloads['status'])) {
            $filters['smart_contract_status_id'] = SmartContractStatus::getByName($payloads['status'])['id'];
        }
        switch ($payloads['role']) {
            case 'Buyer':
                $filters['buyer_user_id'] = $payloads['user_id'];
                break;
            case 'Seller':
                $filters['vendor_id'] = $payloads['user_id'];
                break;
        }

        $response = $smartContractService->getSellerSmartContracts($authorizationDTO, $filters, $perPage);
        return response()->json($response, 200);
    }
}