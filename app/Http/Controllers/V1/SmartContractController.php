<?php


namespace App\Http\Controllers\V1;

use App\Http\Modules\V1\DataTransferObjects\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\UserDTO;
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
            'user_id' => $request->get('user_id')
        ];

        $rules = [
            'user_id' => 'required'
        ];

        $validator = Validator::make($payloads, $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        $authorizationDTO->setBearerToken($request->header('authorization'));

        $userDTO = new UserDTO();
        $userDTO->setId($payloads['user_id']);

        $response = $smartContractService->getSellerSmartContracts($authorizationDTO, $userDTO);

        return response()->json($response, 200);
    }
}