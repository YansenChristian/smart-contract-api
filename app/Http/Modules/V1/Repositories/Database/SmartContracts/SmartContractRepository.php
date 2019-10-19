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

    public function getSellerSmartContracts($filters, $perPage)
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

        $smart_contracts = DB::table('smart_contracts')
            ->join('smart_contract_status',
                'smart_contracts.smart_contract_status_id',
                '=',
                'smart_contract_status.id')
            ->where('vendor_id', '=', $filters['user_id'])
            ->select($columns);

        if(isset($filters['smart_contract_serial'])) {
            $smart_contracts->where(
                'smart_contracts.smart_contract_serial',
                '=',
                $filters['smart_contract_serial']
            );
        }

        if(isset($filters['smart_contract_status_id'])) {
            $smart_contracts->where(
                'smart_contracts.smart_contract_status_id',
                '=',
                $filters['smart_contract_status_id']
            );
        }

        if(isset($filters['start_date']) && isset($filters['end_date'])) {
            $smart_contracts->whereBetween(
                'smart_contracts.created_at', [
                $filters['start_date'],
                $filters['end_date']
            ]);
        }

        return $smart_contracts->paginate($perPage);
    }
}