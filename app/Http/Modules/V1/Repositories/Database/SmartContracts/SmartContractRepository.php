<?php


namespace App\Http\Modules\V1\Repositories\Database\SmartContracts;


use Illuminate\Support\Facades\DB;
use stdClass;

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
            ->where('vendor_id', '=', $filters['vendor_id'])
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

    public function getSellerSmartContract($smartContractSerial)
    {
        $columns = [
            'smart_contracts.id',
            'smart_contracts.smart_contract_serial',
            'smart_contracts.buyer_user_id',
            'smart_contracts.buyer_notes',
            'smart_contracts.on_going_order',
            'smart_contracts.total_order',
            'smart_contracts.total_price',
            'smart_contracts.created_at',
            'smart_contract_status.name AS status',
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_status', 'smart_contracts.smart_contract_status_id', '=', 'smart_contract_status.id')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
            ->first();
    }

    public function getDetail($smartContractId)
    {
        $columns = [
            'smart_contract_details.order_serial'
        ];

        return DB::table('smart_contract_details')
            ->join('smart_contracts', 'smart_contracts.id', '=', 'smart_contract_details.smart_contract_id')
            ->where('smart_contracts.id', '=', $smartContractId)
            ->select($columns)
            ->get();
    }

    public function getLegalContent($smartContractSerial)
    {
        $columns = [
            'smart_contracts.smart_contract_serial',
            'smart_contracts.created_at',
            'smart_contract_details.order_serial'
        ];

        return DB::table('smart_contracts')
            ->join('smart_contract_details', 'smart_contracts.id', '=', 'smart_contract_details.smart_contract_id')
            ->where('smart_contracts.smart_contract_serial', '=', $smartContractSerial)
            ->select($columns)
            ->get();
    }

    public function exists(array $conditions)
    {
        $smartContract = DB::table('smart_contracts');
        foreach ($conditions as $column => $value) {
            $smartContract->where($column, '=', $value);
        }
        return $smartContract->exists();
    }

    public function create($smartContracts)
    {
        return DB::table('smart_contracts')
            ->insertGetId($smartContracts);
    }

    public function createDetail($smartContractDetails)
    {
        return DB::table('smart_contract_details')
            ->insert($smartContractDetails);
    }
}