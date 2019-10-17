<?php


namespace App\Http\Modules\V1\Repositories\Database\SmartContracts;


use Illuminate\Support\Facades\DB;

class SmartContractRepository
{
    public function getCounter()
    {
        $selectStatement = [
            'smart_contract_status.name',
            DB::raw('COUNT(*) AS subtotal')
        ];
        return DB::table('smart_contracts')
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->groupBy('smart_contract_status_id')
            ->select($selectStatement)
            ->get();
    }

    public function getSellerSmartContracts($vendorId, $perPage = 10)
    {
        $columns = [
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.total_price',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.created_at',
            'smart_contract_status.name as status'
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_status', 'smart_contracts.smart_contract_status_id', '=', 'smart_contract_status.id')
            ->where('vendor_id', $vendorId)
            ->select($columns)
            ->paginate($perPage);
    }
}