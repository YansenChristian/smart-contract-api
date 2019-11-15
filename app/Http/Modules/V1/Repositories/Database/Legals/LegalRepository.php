<?php


namespace App\Http\Modules\V1\Repositories\Database\Legals;


use App\Http\Modules\V1\DataTransferObjects\Users\SellerDTO;
use App\Http\Modules\V1\DataTransferObjects\Users\UserDTO;
use Illuminate\Support\Facades\DB;

class LegalRepository
{
    public function getContent($smartContractSerial)
    {
        $columns = [
            'smart_contract_details.created_at',
            'smart_contract_details.order_serial'
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_details', 'smart_contracts.id', '=', 'smart_contract_details.smart_contract_id')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
            ->get();
    }

    public function signContractAsSeller($smartContractSerial, SellerDTO $seller)
    {
        return DB::table('smart_contract_legals')
            ->where('smart_contract_serial', '=', $smartContractSerial)
            ->update([
                'seller_user_id' => $seller->id,
                'vendor_approved_on' => date('Y-m-d H:i:s'),
            ]);
    }

    public function create(array $legalData)
    {
        return DB::table('smart_contract_legals')
            ->insertGetId($legalData);
    }
}