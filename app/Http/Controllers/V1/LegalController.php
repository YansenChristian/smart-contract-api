<?php


namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Http\Modules\V1\DataTransferObjects\Auth\AuthorizationDTO;
use App\Http\Modules\V1\DataTransferObjects\SmartContracts\SmartContractDTO;
use App\Http\Modules\V1\Services\LegalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LegalController extends Controller
{
    public function getContent(Request $request, LegalService $legalService)
    {
        $rules = [
            'smart_contract_serial' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            throw new \Exception($validator->getMessageBag());
        }

        $authorizationDTO = new AuthorizationDTO();
        if($request->hasHeader('Authorization')) {
            $authorizationDTO->bearer = $request->header('Authorization');
        }
        if($request->hasHeader('x-access-token')) {
            $authorizationDTO->x_access_token = $request->header('x-access-token');
        }

        $smartContractDTO = new SmartContractDTO();
        $smartContractDTO->smart_contract_serial = $request->get('smart_contract_serial');

        $response = $legalService->getContent($authorizationDTO, $smartContractDTO);
        return response()->json($response, 200);
    }
}