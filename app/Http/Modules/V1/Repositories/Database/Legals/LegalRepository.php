<?php


namespace App\Http\Modules\V1\Repositories\Database\Legals;


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
}